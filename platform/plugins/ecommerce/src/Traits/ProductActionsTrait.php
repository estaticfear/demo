<?php

namespace Cmat\Ecommerce\Traits;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Ecommerce\Events\ProductQuantityUpdatedEvent;
use Cmat\Ecommerce\Http\Requests\CreateProductWhenCreatingOrderRequest;
use Cmat\Ecommerce\Http\Requests\ProductUpdateOrderByRequest;
use Cmat\Ecommerce\Http\Requests\ProductVersionRequest;
use Cmat\Ecommerce\Http\Resources\AvailableProductResource;
use Cmat\Ecommerce\Repositories\Interfaces\BrandInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductAttributeInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductAttributeSetInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductCollectionInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductVariationInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductVariationItemInterface;
use Cmat\Ecommerce\Services\Products\CreateProductVariationsService;
use Cmat\Ecommerce\Services\Products\StoreAttributesOfProductService;
use Cmat\Ecommerce\Services\Products\StoreProductService;
use EcommerceHelper;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RvMedia;

trait ProductActionsTrait
{
    public function __construct(
        protected ProductInterface $productRepository,
        protected ProductCategoryInterface $productCategoryRepository,
        protected ProductCollectionInterface $productCollectionRepository,
        protected BrandInterface $brandRepository,
        protected ProductAttributeInterface $productAttributeRepository
    ) {
    }

    public function postSaveAllVersions(
        array $versionInRequest,
        ProductVariationInterface $productVariation,
        int|string $id,
        BaseHttpResponse $response,
        bool $isUpdateProduct = true
    ) {
        $product = $this->productRepository->findOrFail($id);

        foreach ($versionInRequest as $variationId => $version) {
            $variation = $productVariation->findById($variationId);

            if (! $variation) {
                continue;
            }

            if (! $variation->product_id || $isUpdateProduct) {
                $isNew = false;
                $productRelatedToVariation = $this->productRepository->findById($variation->product_id);

                if (! $productRelatedToVariation) {
                    $productRelatedToVariation = $this->productRepository->getModel();
                    $isNew = true;
                }

                $version['images'] = array_values(array_filter((array)Arr::get($version, 'images', []) ?: []));

                $productRelatedToVariation->fill($version);

                $productRelatedToVariation->name = $product->name;
                $productRelatedToVariation->status = $product->status;
                $productRelatedToVariation->brand_id = $product->brand_id;
                $productRelatedToVariation->is_variation = 1;

                $productRelatedToVariation->sku = Arr::get($version, 'sku');
                if (! $productRelatedToVariation->sku && Arr::get($version, 'auto_generate_sku')) {
                    $productRelatedToVariation->sku = $product->sku;
                    if (isset($version['attribute_sets']) && is_array($version['attribute_sets'])) {
                        foreach ($version['attribute_sets'] as $attributeId) {
                            $attribute = $this->productAttributeRepository->findById($attributeId);
                            if ($attribute) {
                                $productRelatedToVariation->sku .= '-' . Str::upper($attribute->slug);
                            }
                        }
                    }
                }
                $productRelatedToVariation->price = Arr::get($version, 'price', $product->price);
                $productRelatedToVariation->sale_price = Arr::get($version, 'sale_price', $product->sale_price);
                $productRelatedToVariation->description = Arr::get($version, 'description');

                $productRelatedToVariation->length = Arr::get($version, 'length', $product->length);
                $productRelatedToVariation->wide = Arr::get($version, 'wide', $product->wide);
                $productRelatedToVariation->height = Arr::get($version, 'height', $product->height);
                $productRelatedToVariation->weight = Arr::get($version, 'weight', $product->weight);

                $productRelatedToVariation->sale_type = (int)Arr::get($version, 'sale_type', $product->sale_type);

                if ($productRelatedToVariation->sale_type == 0) {
                    $productRelatedToVariation->start_date = null;
                    $productRelatedToVariation->end_date = null;
                } else {
                    $productRelatedToVariation->start_date = Arr::get($version, 'start_date', $product->start_date);
                    $productRelatedToVariation->end_date = Arr::get($version, 'end_date', $product->end_date);
                }

                if ($isNew) {
                    $productRelatedToVariation->product_type = Arr::get($version, 'product_type', $product->product_type);
                    $productRelatedToVariation->images = json_encode($version['images']);
                }

                $productRelatedToVariation = $this->productRepository->createOrUpdate($productRelatedToVariation);

                if (EcommerceHelper::isEnabledSupportDigitalProducts()) {
                    if ($isNew && $product->productFiles->count()) {
                        foreach ($product->productFiles as $productFile) {
                            $productRelatedToVariation->productFiles()->create($productFile->toArray());
                        }
                    } else {
                        app(StoreProductService::class)->saveProductFiles(request(), $productRelatedToVariation);
                    }
                }

                if (! $productRelatedToVariation->is_variation) {
                    if ($isNew) {
                        event(new CreatedContentEvent(PRODUCT_MODULE_SCREEN_NAME, request(), $productRelatedToVariation));
                    } else {
                        event(new UpdatedContentEvent(PRODUCT_MODULE_SCREEN_NAME, request(), $productRelatedToVariation));
                    }
                }

                event(new ProductQuantityUpdatedEvent($variation->product));

                $variation->product_id = $productRelatedToVariation->id;
            }

            $variation->is_default = Arr::get($version, 'variation_default_id', 0) == $variation->id;

            $productVariation->createOrUpdate($variation);

            if (isset($version['attribute_sets']) && is_array($version['attribute_sets'])) {
                $variation->productAttributes()->sync($version['attribute_sets']);
            }
        }

        return $response->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function postAddAttributeToProduct(
        int|string $id,
        Request $request,
        ProductVariationInterface $variationRepository,
        BaseHttpResponse $response,
        StoreAttributesOfProductService $storeAttributesOfProductService
    ) {
        $product = $this->productRepository->findOrFail($id);

        $addedAttributes = $request->input('added_attributes', []);
        if ($addedAttributes) {
            foreach ($addedAttributes as $key => $addedAttribute) {
                if (empty($addedAttribute)) {
                    unset($addedAttributes[$key]);
                }
            }

            if (! empty($addedAttributes)) {
                $result = $variationRepository->getVariationByAttributesOrCreate($id, $addedAttributes);

                $addedAttributeSets = $request->input('added_attribute_sets', []);
                foreach ($addedAttributeSets as $key => $addedAttributeSet) {
                    if (empty($addedAttributeSet)) {
                        unset($addedAttributeSets[$key]);
                    }
                }

                $storeAttributesOfProductService->execute($product, $addedAttributeSets, $addedAttributes);

                $variation = $result['variation']->toArray();
                $variation['variation_default_id'] = $variation['id'];
                $variation['auto_generate_sku'] = true;

                $this->postSaveAllVersions([$variation['id'] => $variation], $variationRepository, $id, $response);
            }
        }

        return $response->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $product = $this->productRepository->findOrFail($id);

        try {
            $this->productRepository->deleteBy(['id' => $id]);
            event(new DeletedContentEvent(PRODUCT_MODULE_SCREEN_NAME, $request, $product));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $product = $this->productRepository->findOrFail($id);
            $this->productRepository->delete($product);
            event(new DeletedContentEvent(PRODUCT_MODULE_SCREEN_NAME, $request, $product));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function deleteVersion(
        ProductVariationInterface $productVariation,
        ProductVariationItemInterface $productVariationItem,
        int|string|null $variationId,
        BaseHttpResponse $response
    ) {
        $result = $this->deleteVersionItem($productVariation, $productVariationItem, $variationId);

        if ($result) {
            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        }

        return $response
            ->setError()
            ->setMessage(trans('core/base::notices.delete_error_message'));
    }

    public function deleteVersions(
        Request $request,
        ProductVariationInterface $productVariation,
        ProductVariationItemInterface $productVariationItem,
        BaseHttpResponse $response
    ) {
        $ids = (array)$request->input('ids');

        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $this->deleteVersionItem($productVariation, $productVariationItem, $id);
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    protected function deleteVersionItem(
        ProductVariationInterface $productVariation,
        ProductVariationItemInterface $productVariationItem,
        int|string $variationId
    ) {
        $variation = $productVariation->findById($variationId);

        if (! $variation) {
            return true;
        }

        $productVariationItem->deleteBy(['variation_id' => $variationId]);
        $productRelatedToVariation = $this->productRepository->findById($variation->product_id);

        if ($productRelatedToVariation) {
            event(new DeletedContentEvent(PRODUCT_MODULE_SCREEN_NAME, request(), $productRelatedToVariation));
        }

        $this->productRepository->deleteBy(['id' => $variation->product_id]);
        $result = $productVariation->delete($variation);

        $originProduct = $this->productRepository->findById($variation->configurable_product_id);

        if ($variation->is_default) {
            $latestVariation = $productVariation->getFirstBy(['configurable_product_id' => $variation->configurable_product_id]);
            if ($latestVariation) {
                $latestVariation->is_default = 1;

                $productVariation->createOrUpdate($latestVariation);

                if ($originProduct && $latestVariation->product->id) {
                    $originProduct->sku = $latestVariation->product->sku;
                    $originProduct->price = $latestVariation->product->price;
                    $originProduct->length = $latestVariation->product->length;
                    $originProduct->wide = $latestVariation->product->wide;
                    $originProduct->height = $latestVariation->product->height;
                    $originProduct->weight = $latestVariation->product->weight;
                    $originProduct->sale_price = $latestVariation->product->sale_price;
                    $originProduct->sale_type = $latestVariation->product->sale_type;
                    $originProduct->start_date = $latestVariation->product->start_date;
                    $originProduct->end_date = $latestVariation->product->end_date;
                    $this->productRepository->createOrUpdate($originProduct);
                }
            } else {
                $originProduct->productAttributeSets()->detach();
            }
        }

        event(new ProductQuantityUpdatedEvent($originProduct));

        return $result;
    }

    public function postAddVersion(
        ProductVersionRequest $request,
        ProductVariationInterface $productVariation,
        int|string|null $id,
        BaseHttpResponse $response
    ) {
        $addedAttributes = $request->input('attribute_sets', []);

        if (! empty($addedAttributes) && is_array($addedAttributes)) {
            $result = $productVariation->getVariationByAttributesOrCreate($id, $addedAttributes);
            if (! $result['created']) {
                return $response
                    ->setError()
                    ->setMessage(trans('plugins/ecommerce::products.form.variation_existed'));
            }

            $this->postSaveAllVersions(
                [$result['variation']->id => $request->input()],
                $productVariation,
                $id,
                $response
            );

            return $response->setMessage(trans('plugins/ecommerce::products.form.added_variation_success'));
        }

        return $response
            ->setError()
            ->setMessage(trans('plugins/ecommerce::products.form.no_attributes_selected'));
    }

    public function getVersionForm(
        int|string|null $id,
        Request $request,
        ProductVariationInterface $productVariation,
        BaseHttpResponse $response,
        ProductAttributeSetInterface $productAttributeSetRepository,
        ProductVariationItemInterface $productVariationItemRepository
    ) {
        $product = null;
        $variation = null;
        $productVariationsInfo = collect();

        if ($id) {
            $variation = $productVariation->findOrFail($id);
            $product = $this->productRepository->findOrFail($variation->product_id);
            $productVariationsInfo = $productVariationItemRepository->getVariationsInfo([$id]);
            $originalProduct = $product;
        } else {
            $originalProduct = $this->productRepository->findOrFail($request->input('product_id'));
        }

        $productId = $variation ? $variation->configurable_product_id : $request->input('product_id');

        if ($productId) {
            $productAttributeSets = $productAttributeSetRepository->getByProductId($productId);
        } else {
            $productAttributeSets = $productAttributeSetRepository->getAllWithSelected($productId);
        }

        return $response
            ->setData(
                view('plugins/ecommerce::products.partials.product-variation-form', compact(
                    'productAttributeSets',
                    'product',
                    'productVariationsInfo',
                    'originalProduct'
                ))->render()
            );
    }

    public function postUpdateVersion(
        ProductVersionRequest $request,
        ProductVariationInterface $productVariation,
        int|string $id,
        BaseHttpResponse $response
    ) {
        $variation = $productVariation->findOrFail($id);

        $addedAttributes = $request->input('attribute_sets', []);

        if (! empty($addedAttributes) && is_array($addedAttributes)) {
            $result = $productVariation->getVariationByAttributesOrCreate(
                $variation->configurable_product_id,
                $addedAttributes
            );

            if (! $result['created'] && $result['variation']->id !== $variation->id) {
                return $response
                    ->setError()
                    ->setMessage(trans('plugins/ecommerce::products.form.variation_existed'));
            }

            if ($variation->is_default) {
                $request->merge([
                    'variation_default_id' => $variation->id,
                ]);
            }

            $this->postSaveAllVersions(
                [$variation->id => $request->input()],
                $productVariation,
                $variation->configurable_product_id,
                $response
            );

            $productVariation->deleteBy(['product_id' => null]);

            return $response->setMessage(trans('plugins/ecommerce::products.form.updated_variation_success'));
        }

        return $response
            ->setError()
            ->setMessage(trans('plugins/ecommerce::products.form.no_attributes_selected'));
    }

    public function postGenerateAllVersions(
        CreateProductVariationsService $service,
        ProductVariationInterface $productVariation,
        int|string $id,
        BaseHttpResponse $response
    ) {
        $product = $this->productRepository->findOrFail($id);

        $variations = $service->execute($product);

        $variationInfo = [];

        foreach ($variations as $variation) {
            /**
             * @var Collection $variation
             */
            $data = $variation->toArray();
            if ((int)$variation->is_default === 1) {
                $data['variation_default_id'] = $variation->id;
            }

            $variationInfo[$variation->id] = $data;
        }

        $this->postSaveAllVersions($variationInfo, $productVariation, $id, $response, false);

        return $response->setMessage(trans('plugins/ecommerce::products.form.created_all_variation_success'));
    }

    public function postStoreRelatedAttributes(
        Request $request,
        StoreAttributesOfProductService $service,
        int|string $id,
        BaseHttpResponse $response
    ) {
        $product = $this->productRepository->findOrFail($id);

        $attributeSets = $request->input('attribute_sets', []);

        $service->execute($product, $attributeSets);

        return $response->setMessage(trans('plugins/ecommerce::products.form.updated_product_attributes_success'));
    }

    public function getListProductForSearch(Request $request, BaseHttpResponse $response)
    {
        $availableProducts = $this->productRepository
            ->advancedGet([
                'condition' => [
                    'status' => BaseStatusEnum::PUBLISHED,
                    ['is_variation', '<>', 1],
                    ['id', '<>', $request->input('product_id', 0)],
                    ['name', 'LIKE', '%' . $request->input('keyword') . '%'],
                ],
                'select' => [
                    'id',
                    'name',
                    'images',
                    'image',
                    'price',
                ],
                'paginate' => [
                    'per_page' => 5,
                    'type' => 'simplePaginate',
                    'current_paged' => (int)$request->input('page', 1),
                ],
            ]);

        $includeVariation = $request->input('include_variation', 0);

        return $response->setData(
            view('plugins/ecommerce::products.partials.panel-search-data', compact(
                'availableProducts',
                'includeVariation'
            ))->render()
        );
    }

    public function getRelationBoxes(int|string|null $id, BaseHttpResponse $response)
    {
        $product = null;
        if ($id) {
            $product = $this->productRepository->findById($id);
        }

        $dataUrl = route('products.get-list-product-for-search', ['product_id' => $product ? $product->id : 0]);

        return $response->setData(view(
            'plugins/ecommerce::products.partials.extras',
            compact('product', 'dataUrl')
        )->render());
    }

    public function getListProductForSelect(Request $request, BaseHttpResponse $response)
    {
        $availableProducts = $this->productRepository
            ->getModel()
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->where('is_variation', '<>', 1)
            ->where('name', 'LIKE', '%' . $request->input('keyword') . '%')
            ->select([
                'ec_products.*',
            ])
            ->distinct('ec_products.id');

        $includeVariation = $request->input('include_variation', 0);
        if ($includeVariation) {
            /**
             * @var Builder $availableProducts
             */
            $availableProducts = $availableProducts
                ->join('ec_product_variations', 'ec_product_variations.configurable_product_id', '=', 'ec_products.id')
                ->join(
                    'ec_product_variation_items',
                    'ec_product_variation_items.variation_id',
                    '=',
                    'ec_product_variations.id'
                );
        }

        $availableProducts = $availableProducts->simplePaginate(5);

        foreach ($availableProducts as &$availableProduct) {
            $image = Arr::first($availableProduct->images) ?? null;
            $availableProduct->image_url = RvMedia::getImageUrl($image, 'thumb', false, RvMedia::getDefaultImage());
            $availableProduct->price = $availableProduct->front_sale_price;
            if ($includeVariation) {
                foreach ($availableProduct->variations as &$variation) {
                    $variation->price = $variation->product->front_sale_price;
                    foreach ($variation->variationItems as &$variationItem) {
                        $variationItem->attribute_title = $variationItem->attribute->title;
                    }
                }
            }
        }

        return $response->setData($availableProducts);
    }

    public function postCreateProductWhenCreatingOrder(
        CreateProductWhenCreatingOrderRequest $request,
        BaseHttpResponse $response
    ) {
        $product = $this->productRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(PRODUCT_MODULE_SCREEN_NAME, $request, $product));

        return $response
            ->setData(new AvailableProductResource($product))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function getAllProductAndVariations(Request $request, BaseHttpResponse $response)
    {
        $selectedProducts = collect();
        if ($productIds = $request->input('product_ids', [])) {
            $selectedProducts = $this->productRepository
                ->getModel()
                ->where('status', BaseStatusEnum::PUBLISHED)
                ->whereIn('id', $productIds)
                ->with(['variationInfo.configurableProduct'])
                ->get();
        }


        $availableProducts = $this->productRepository
            ->getModel()
            ->select(['ec_products.*'])
            ->where([
                ['status', '=', BaseStatusEnum::PUBLISHED],
                ['is_variation', '<>', 1],
            ])
            ->with(['variationInfo.configurableProduct']);

        if ($keyword = $request->input('keyword')) {
            $availableProducts = $availableProducts->where('name', 'LIKE', '%' . $keyword . '%');
        }

        if (is_plugin_active('marketplace') && $selectedProducts->count()) {
            $selectedProducts = $selectedProducts->map(function ($item) {
                if ($item->is_variation) {
                    $item->store_id = $item->original_product->store_id;
                }

                if (! $item->store_id) {
                    $item->store_id = 0;
                }

                return $item;
            });
            $storeIds = array_unique($selectedProducts->pluck('store_id')->all());
            $availableProducts = $availableProducts->whereIn('store_id', $storeIds)->with(['store']);
        }

        // Lọc theo type
        if ($product_type = $request->input('product_type')) {
            $availableProducts = $availableProducts->where('product_type', $product_type);
        }

        $availableProducts = $availableProducts->simplePaginate(5);

        return $response->setData(AvailableProductResource::collection($availableProducts)->response()->getData());
    }

    public function postUpdateOrderBy(ProductUpdateOrderByRequest $request, BaseHttpResponse $response)
    {
        $product = $this->productRepository->findOrFail($request->input('pk'));
        $product->order = $request->input('value', 0);
        $this->productRepository->createOrUpdate($product);

        return $response->setMessage(trans('core/base::notices.update_success_message'));
    }
}
