<?php

namespace Cmat\Ecommerce\Http\Controllers;

use Cmat\Base\Events\BeforeEditContentEvent;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Base\Forms\FormBuilder;
use Cmat\Base\Http\Controllers\BaseController;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Ecommerce\Forms\ProductTagForm;
use Cmat\Ecommerce\Http\Requests\ProductTagRequest;
use Cmat\Ecommerce\Repositories\Interfaces\ProductTagInterface;
use Cmat\Ecommerce\Tables\ProductTagTable;
use Exception;
use Illuminate\Http\Request;

class ProductTagController extends BaseController
{
    public function __construct(protected ProductTagInterface $productTagRepository)
    {
    }

    public function index(ProductTagTable $table)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-tag.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-tag.create'));

        return $formBuilder->create(ProductTagForm::class)->renderForm();
    }

    public function store(ProductTagRequest $request, BaseHttpResponse $response)
    {
        $productTag = $this->productTagRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(PRODUCT_TAG_MODULE_SCREEN_NAME, $request, $productTag));

        return $response
            ->setPreviousUrl(route('product-tag.index'))
            ->setNextUrl(route('product-tag.edit', $productTag->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $productTag = $this->productTagRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $productTag));

        page_title()->setTitle(trans('plugins/ecommerce::product-tag.edit') . ' "' . $productTag->name . '"');

        return $formBuilder->create(ProductTagForm::class, ['model' => $productTag])->renderForm();
    }

    public function update(int|string $id, ProductTagRequest $request, BaseHttpResponse $response)
    {
        $productTag = $this->productTagRepository->findOrFail($id);

        $productTag->fill($request->input());

        $this->productTagRepository->createOrUpdate($productTag);

        event(new UpdatedContentEvent(PRODUCT_TAG_MODULE_SCREEN_NAME, $request, $productTag));

        return $response
            ->setPreviousUrl(route('product-tag.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $productTag = $this->productTagRepository->findOrFail($id);

            $this->productTagRepository->delete($productTag);

            event(new DeletedContentEvent(PRODUCT_TAG_MODULE_SCREEN_NAME, $request, $productTag));

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
            $productTag = $this->productTagRepository->findOrFail($id);
            $this->productTagRepository->delete($productTag);
            event(new DeletedContentEvent(PRODUCT_TAG_MODULE_SCREEN_NAME, $request, $productTag));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function getAllTags()
    {
        return $this->productTagRepository->pluck('name');
    }
}
