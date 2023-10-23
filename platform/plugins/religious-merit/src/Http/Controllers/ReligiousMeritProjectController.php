<?php

namespace Cmat\ReligiousMerit\Http\Controllers;

use Cmat\ACL\Models\User;
use Cmat\Base\Events\BeforeEditContentEvent;
use Cmat\ReligiousMerit\Http\Requests\ReligiousMeritProjectRequest;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProjectInterface;
use Cmat\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Cmat\ReligiousMerit\Tables\ReligiousMeritProjectTable;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\ReligiousMerit\Forms\ReligiousMeritProjectForm;
use Cmat\Base\Forms\FormBuilder;
use Cmat\Ecommerce\Http\Resources\CartItemResource;
use Cmat\Ecommerce\Repositories\Interfaces\ProductInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Cmat\ReligiousMerit\Models\ReligiousMeritProjectProduct;
use Cmat\Ecommerce\Cart\CartItem;
use BaseHelper;

class ReligiousMeritProjectController extends BaseController
{
    protected ReligiousMeritProjectInterface $religiousMeritProjectRepository;
    protected ProductInterface $productRepository;

    public function __construct(ReligiousMeritProjectInterface $religiousMeritProjectRepository, ProductInterface $productRepository)
    {
        $this->religiousMeritProjectRepository = $religiousMeritProjectRepository;
        $this->productRepository = $productRepository;
    }

    public function getProjectProducts(Request $request, BaseHttpResponse $response) {
        $project_id = $request->project_id;
        $product_type = $request->product_type;
        $project = $this->religiousMeritProjectRepository->findById($project_id);
        if (!$project) {
            return $response
            ->setError()
            ->setData([])
            ->setMessage(__('Không tìm thấy hoạt động được yêu cầu'));
        };
        $products = $project->products($product_type)->get()->toArray();
        return $response
        ->setData($products)
        ->setMessage(__('Success'));
    }

    public function updateProjectProducts(Request $request, BaseHttpResponse $response) {
        $project_id = $request->input('project_id');
        $product_type = $request->input('product_type');
        $project = $this->religiousMeritProjectRepository->findById($project_id);
        if (!$project) {
            return $response
            ->setError()
            ->setMessage(__('Không tìm thấy hoạt động được yêu cầu'));
        };

        // delete
        $deleted_product_ids = $request->input('deleted_product_ids');
        ReligiousMeritProjectProduct::where('merit_project_id', $project_id)->where('product_type', $product_type)
            ->where(function($query) {
                $query->whereNull('total_merit_qty')->orWhere('total_merit_qty', 0);
            })
            ->whereIn('product_id', $deleted_product_ids)
            ->delete();

        $cartItems = collect();
        $productItems = collect();

        $with = ['productCollections', 'variationInfo', 'variationInfo.configurableProduct', 'variationProductAttributes'];
        $inputProducts = collect($request->input('products'));
        if ($productIds = $inputProducts->pluck('id')->all()) {
            $products = $this->productRepository->getModel()
                ->whereIn('id', $productIds)
                ->where('product_type', $product_type)
                ->with($with)
                ->get();
        } else {
            $products = collect();
        }

        foreach ($inputProducts as $inputProduct) {
            $productId = $inputProduct['id'];
            $product = $products->firstWhere('id', $productId);
            if (! $product) {
                continue;
            }
            $productName = $product->original_product->name ?: $product->name;

            $cartItemsById = $cartItems->where('id', $productId);

            $inputQty = Arr::get($inputProduct, 'quantity') ?: 1;
            $qty = $inputQty;
            $qtySelected = 0;
            if ($cartItemsById->count()) {
                $qtySelected = $cartItemsById->sum('qty');
            }

            $originalQuantity = $product->quantity;
            $product->quantity = (int)$product->quantity - $qtySelected - $inputQty + 1;

            if ($product->quantity < 0) {
                $product->quantity = 0;
            }

            $product->quantity = $originalQuantity;

            $parentProduct = $product->original_product;

            $image = $product->image ?: $parentProduct->image;
            $options = [
                'name' => $productName,
                'image' => $image,
                'attributes' => $product->is_variation ? $product->variation_attributes : '',
                'extras' => [],
                'sku' => $product->sku,
                'weight' => $product->original_product->weight,
                'original_price' => $product->original_price,
                'product_link' => route('products.edit', $product->original_product->id),
                'product_type' => $product->product_type,
                'is_not_allow_merit_a_part' => Arr::get($inputProduct, 'is_not_allow_merit_a_part') ?: false
            ];
            $price = $product->original_price;

            $cartItem = CartItem::fromAttributes($product->id, BaseHelper::clean($parentProduct->name ?: $product->name), $price, $options);

            $cartItemExists = $cartItems->firstWhere('rowId', $cartItem->rowId);

            if (! $cartItemExists) {
                $cartItem->setQuantity($qty);
                $cartItems[] = $cartItem;
                $product->cartItem = $cartItem;
                $productItems[] = $product;
            }
        }

        $products = CartItemResource::collection($cartItems);

        $oldProjectProducts = $project->products($product_type)->get();
        foreach ($products as $productItem) {
            $productItem = $productItem->toArray($request);
            $product_id = Arr::get($productItem, 'id');
            if ($oldProjectProducts->count()) {
                $oldProjectProduct = $oldProjectProducts->first(function($item) use ($product_id) {
                    return $item->product_id == $product_id;
                });
                if ($oldProjectProduct) {
                    ReligiousMeritProjectProduct::where('id', $oldProjectProduct->id)->update([
                        'qty' => Arr::get($productItem, 'quantity', 1),
                        'price' => Arr::get($productItem, 'original_price'),
                        'is_not_allow_merit_a_part' => Arr::get($productItem, 'is_not_allow_merit_a_part'),
                    ]);
                } else {
                    $projectProduct = [
                        'merit_project_id' => $project->id,
                        'product_id' => Arr::get($productItem, 'id'),
                        'product_name' => Arr::get($productItem, 'name'),
                        'qty' => Arr::get($productItem, 'quantity', 1),
                        'price' => Arr::get($productItem, 'original_price'),
                        'product_type' => Arr::get($productItem, 'product_type'),
                        'is_not_allow_merit_a_part' => Arr::get($productItem, 'is_not_allow_merit_a_part'),
                    ];
                    ReligiousMeritProjectProduct::create($projectProduct);
                }
            } else {
                $projectProduct = [
                    'merit_project_id' => $project->id,
                    'product_id' => Arr::get($productItem, 'id'),
                    'product_name' => Arr::get($productItem, 'name'),
                    'qty' => Arr::get($productItem, 'quantity', 1),
                    'price' => Arr::get($productItem, 'original_price'),
                    'product_type' => Arr::get($productItem, 'product_type'),
                    'is_not_allow_merit_a_part' => Arr::get($productItem, 'is_not_allow_merit_a_part'),
                ];
                ReligiousMeritProjectProduct::create($projectProduct);
            }
        }

        return $response
            ->setMessage(__('Xóa hiện vật/công sức khỏi dự án thành công'));

        return $response
            ->setMessage(__('Cập nhật hiện vật/công sức vào dự án thành công'));
    }

    public function index(ReligiousMeritProjectTable $table)
    {
        page_title()->setTitle(trans('plugins/religious-merit::religious-merit.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/religious-merit::religious-merit.create'));

        return $formBuilder->create(ReligiousMeritProjectForm::class)->renderForm();
    }

    public function store(ReligiousMeritProjectRequest $request, BaseHttpResponse $response)
    {
        $religiousMerit = $this->religiousMeritProjectRepository->createOrUpdate(
            array_merge($request->input(), [
                'author_id' => Auth::id(),
                'author_type' => User::class,
            ])
        );

        event(new CreatedContentEvent(RELIGIOUS_MERIT_MODULE_SCREEN_NAME, $request, $religiousMerit));

        return $response
            ->setPreviousUrl(route('religious-merit-project.index'))
            ->setNextUrl(route('religious-merit-project.edit', $religiousMerit->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $religiousMerit = $this->religiousMeritProjectRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $religiousMerit));

        page_title()->setTitle(trans('plugins/religious-merit::religious-merit.edit') . ' "' . $religiousMerit->name . '"');

        return $formBuilder->create(ReligiousMeritProjectForm::class, ['model' => $religiousMerit])->renderForm();
    }

    public function update(int|string $id, ReligiousMeritProjectRequest $request, BaseHttpResponse $response)
    {
        $religiousMerit = $this->religiousMeritProjectRepository->findOrFail($id);

        $religiousMerit->fill($request->input());

        $religiousMerit = $this->religiousMeritProjectRepository->createOrUpdate($religiousMerit);

        event(new UpdatedContentEvent(RELIGIOUS_MERIT_MODULE_SCREEN_NAME, $request, $religiousMerit));

        return $response
            ->setPreviousUrl(route('religious-merit-project.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $religiousMerit = $this->religiousMeritProjectRepository->findOrFail($id);

            $this->religiousMeritProjectRepository->delete($religiousMerit);

            event(new DeletedContentEvent(RELIGIOUS_MERIT_MODULE_SCREEN_NAME, $request, $religiousMerit));

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
            $religiousMerit = $this->religiousMeritProjectRepository->findOrFail($id);
            $this->religiousMeritProjectRepository->delete($religiousMerit);
            event(new DeletedContentEvent(RELIGIOUS_MERIT_MODULE_SCREEN_NAME, $request, $religiousMerit));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function getProjectDetail(int|string $id, BaseHttpResponse $response)
    {
        $project = $this->religiousMeritProjectRepository->findOrFail($id);

        return $response
        ->setData($project)
        ->setMessage(__('Success'));
    }
}
