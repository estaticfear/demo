<?php

namespace Cmat\Ecommerce\Http\Controllers;

use Assets;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Base\Forms\FormAbstract;
use Cmat\Base\Forms\FormBuilder;
use Cmat\Base\Http\Controllers\BaseController;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Ecommerce\Forms\ProductCategoryForm;
use Cmat\Ecommerce\Http\Requests\ProductCategoryRequest;
use Cmat\Ecommerce\Http\Resources\ProductCategoryResource;
use Cmat\Ecommerce\Models\ProductCategory;
use Cmat\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCategoryController extends BaseController
{
    public function __construct(protected ProductCategoryInterface $productCategoryRepository)
    {
    }

    public function index(FormBuilder $formBuilder, Request $request, BaseHttpResponse $response)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-categories.name'));

        $categories = $this->productCategoryRepository->getProductCategories([], ['slugable'], ['products']);

        if ($request->ajax()) {
            $data = view('core/base::forms.partials.tree-categories', $this->getOptions(compact('categories')))
                ->render();

            return $response->setData($data);
        }

        Assets::addStylesDirectly(['vendor/core/core/base/css/tree-category.css'])
            ->addScriptsDirectly(['vendor/core/core/base/js/tree-category.js']);

        $form = $formBuilder->create(ProductCategoryForm::class, ['template' => 'core/base::forms.form-tree-category']);
        $form = $this->setFormOptions($form, null, compact('categories'));

        return $form->renderForm();
    }

    public function create(FormBuilder $formBuilder, Request $request, BaseHttpResponse $response)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-categories.create'));

        if ($request->ajax()) {
            return $response->setData($this->getForm());
        }

        return $formBuilder->create(ProductCategoryForm::class)->renderForm();
    }

    public function store(ProductCategoryRequest $request, BaseHttpResponse $response)
    {
        $productCategory = $this->productCategoryRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(PRODUCT_CATEGORY_MODULE_SCREEN_NAME, $request, $productCategory));

        if ($request->ajax()) {
            $productCategory = $this->productCategoryRepository->findOrFail($productCategory->id);

            if ($request->input('submit') == 'save') {
                $form = $this->getForm();
            } else {
                $form = $this->getForm($productCategory);
            }

            $response->setData([
                'model' => $productCategory,
                'form' => $form,
            ]);
        }

        return $response
                ->setPreviousUrl(route('product-categories.index'))
                ->setNextUrl(route('product-categories.edit', $productCategory->id))
                ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request, BaseHttpResponse $response)
    {
        $productCategory = $this->productCategoryRepository->findOrFail($id);

        if ($request->ajax()) {
            return $response->setData($this->getForm($productCategory));
        }

        page_title()->setTitle(trans('plugins/ecommerce::product-categories.edit') . ' "' . $productCategory->name . '"');

        return $formBuilder->create(ProductCategoryForm::class, ['model' => $productCategory])->renderForm();
    }

    public function update(int|string $id, ProductCategoryRequest $request, BaseHttpResponse $response)
    {
        $productCategory = $this->productCategoryRepository->findOrFail($id);
        $productCategory->fill($request->input());

        $this->productCategoryRepository->createOrUpdate($productCategory);
        event(new UpdatedContentEvent(PRODUCT_CATEGORY_MODULE_SCREEN_NAME, $request, $productCategory));

        if ($request->ajax()) {
            $productCategory = $this->productCategoryRepository->findOrFail($id);

            if ($request->input('submit') == 'save') {
                $form = $this->getForm();
            } else {
                $form = $this->getForm($productCategory);
            }
            $response->setData([
                'model' => $productCategory,
                'form' => $form,
            ]);
        }

        return $response
                ->setPreviousUrl(route('product-categories.index'))
                ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $productCategory = $this->productCategoryRepository->findOrFail($id);

            $this->productCategoryRepository->delete($productCategory);
            event(new DeletedContentEvent(PRODUCT_CATEGORY_MODULE_SCREEN_NAME, $request, $productCategory));

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
            $productCategory = $this->productCategoryRepository->findOrFail($id);
            $this->productCategoryRepository->delete($productCategory);
            event(new DeletedContentEvent(PRODUCT_CATEGORY_MODULE_SCREEN_NAME, $request, $productCategory));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    protected function getForm(?ProductCategory $model = null): string
    {
        $options = ['template' => 'core/base::forms.form-no-wrap'];
        if ($model) {
            $options['model'] = $model;
        }

        $form = app(FormBuilder::class)->create(ProductCategoryForm::class, $options);

        $form = $this->setFormOptions($form, $model);

        return $form->renderForm();
    }

    protected function setFormOptions(FormAbstract $form, ?ProductCategory $model = null, array $options = [])
    {
        if (! $model) {
            $form->setUrl(route('product-categories.create'));
        }

        if (! Auth::user()->hasPermission('product-categories.create') && ! $model) {
            $class = $form->getFormOption('class');
            $form->setFormOption('class', $class . ' d-none');
        }

        $form->setFormOptions($this->getOptions($options));

        return $form;
    }

    protected function getOptions(array $options = []): array
    {
        return array_merge([
            'canCreate' => Auth::user()->hasPermission('product-categories.create'),
            'canEdit' => Auth::user()->hasPermission('product-categories.edit'),
            'canDelete' => Auth::user()->hasPermission('product-categories.destroy'),
            'createRoute' => 'product-categories.create',
            'editRoute' => 'product-categories.edit',
            'deleteRoute' => 'product-categories.destroy',
        ], $options);
    }

    public function getSearch(Request $request, BaseHttpResponse $response)
    {
        $term = $request->input('search');

        $categories = $this->productCategoryRepository
                ->select(['id', 'name'])
                ->where('name', 'LIKE', '%' . $term . '%')
                ->paginate(10);

        $data = ProductCategoryResource::collection($categories);

        return $response->setData($data)->toApiResponse();
    }
}
