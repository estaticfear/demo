<?php

namespace Cmat\Ecommerce\Http\Controllers;

use Cmat\Base\Events\BeforeEditContentEvent;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Base\Forms\FormBuilder;
use Cmat\Base\Http\Controllers\BaseController;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Ecommerce\Forms\ProductCollectionForm;
use Cmat\Ecommerce\Http\Requests\ProductCollectionRequest;
use Cmat\Ecommerce\Repositories\Interfaces\ProductCollectionInterface;
use Cmat\Ecommerce\Tables\ProductCollectionTable;
use Exception;
use Illuminate\Http\Request;

class ProductCollectionController extends BaseController
{
    public function __construct(protected ProductCollectionInterface $productCollectionRepository)
    {
    }

    public function index(ProductCollectionTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-collections.name'));

        return $dataTable->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-collections.create'));

        return $formBuilder->create(ProductCollectionForm::class)->renderForm();
    }

    public function store(ProductCollectionRequest $request, BaseHttpResponse $response)
    {
        $productCollection = $this->productCollectionRepository->getModel();
        $productCollection->fill($request->input());

        $productCollection->slug = $this->productCollectionRepository->createSlug($request->input('slug'), 0);

        $productCollection = $this->productCollectionRepository->createOrUpdate($productCollection);

        event(new CreatedContentEvent(PRODUCT_COLLECTION_MODULE_SCREEN_NAME, $request, $productCollection));

        return $response
            ->setPreviousUrl(route('product-collections.index'))
            ->setNextUrl(route('product-collections.edit', $productCollection->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $productCollection = $this->productCollectionRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $productCollection));

        page_title()->setTitle(trans('plugins/ecommerce::product-collections.edit') . ' "' . $productCollection->name . '"');

        return $formBuilder
            ->create(ProductCollectionForm::class, ['model' => $productCollection])
            ->remove('slug')
            ->renderForm();
    }

    public function update(int|string $id, ProductCollectionRequest $request, BaseHttpResponse $response)
    {
        $productCollection = $this->productCollectionRepository->findOrFail($id);
        $productCollection->fill($request->input());

        $productCollection = $this->productCollectionRepository->createOrUpdate($productCollection);

        event(new UpdatedContentEvent(PRODUCT_COLLECTION_MODULE_SCREEN_NAME, $request, $productCollection));

        return $response
            ->setPreviousUrl(route('product-collections.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, BaseHttpResponse $response, Request $request)
    {
        $productCollection = $this->productCollectionRepository->findOrFail($id);

        try {
            $this->productCollectionRepository->delete($productCollection);

            event(new DeletedContentEvent(PRODUCT_COLLECTION_MODULE_SCREEN_NAME, $request, $productCollection));

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
            $productCollection = $this->productCollectionRepository->findOrFail($id);
            $this->productCollectionRepository->delete($productCollection);
            event(new DeletedContentEvent(PRODUCT_COLLECTION_MODULE_SCREEN_NAME, $request, $productCollection));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function getListForSelect(BaseHttpResponse $response)
    {
        $productCollections = $this->productCollectionRepository
            ->getModel()
            ->select(['id', 'name'])
            ->get()
            ->toArray();

        return $response->setData($productCollections);
    }
}
