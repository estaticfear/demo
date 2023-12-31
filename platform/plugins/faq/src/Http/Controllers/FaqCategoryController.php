<?php

namespace Cmat\Faq\Http\Controllers;

use Cmat\Base\Events\BeforeEditContentEvent;
use Cmat\Faq\Http\Requests\FaqCategoryRequest;
use Cmat\Faq\Repositories\Interfaces\FaqCategoryInterface;
use Cmat\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Cmat\Faq\Tables\FaqCategoryTable;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Faq\Forms\FaqCategoryForm;
use Cmat\Base\Forms\FormBuilder;

class FaqCategoryController extends BaseController
{
    public function __construct(protected FaqCategoryInterface $faqCategoryRepository)
    {
    }

    public function index(FaqCategoryTable $table)
    {
        page_title()->setTitle(trans('plugins/faq::faq-category.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/faq::faq-category.create'));

        return $formBuilder->create(FaqCategoryForm::class)->renderForm();
    }

    public function store(FaqCategoryRequest $request, BaseHttpResponse $response)
    {
        $faqCategory = $this->faqCategoryRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(FAQ_CATEGORY_MODULE_SCREEN_NAME, $request, $faqCategory));

        return $response
            ->setPreviousUrl(route('faq_category.index'))
            ->setNextUrl(route('faq_category.edit', $faqCategory->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $faqCategory = $this->faqCategoryRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $faqCategory));

        page_title()->setTitle(trans('core/base::forms.edit_item', ['name' => $faqCategory->name]));

        return $formBuilder->create(FaqCategoryForm::class, ['model' => $faqCategory])->renderForm();
    }

    public function update(int|string $id, FaqCategoryRequest $request, BaseHttpResponse $response)
    {
        $faqCategory = $this->faqCategoryRepository->findOrFail($id);

        $faqCategory->fill($request->input());

        $this->faqCategoryRepository->createOrUpdate($faqCategory);

        event(new UpdatedContentEvent(FAQ_CATEGORY_MODULE_SCREEN_NAME, $request, $faqCategory));

        return $response
            ->setPreviousUrl(route('faq_category.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $faqCategory = $this->faqCategoryRepository->findOrFail($id);

            $this->faqCategoryRepository->delete($faqCategory);

            event(new DeletedContentEvent(FAQ_CATEGORY_MODULE_SCREEN_NAME, $request, $faqCategory));

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
            $faqCategory = $this->faqCategoryRepository->findOrFail($id);
            $this->faqCategoryRepository->delete($faqCategory);
            event(new DeletedContentEvent(FAQ_CATEGORY_MODULE_SCREEN_NAME, $request, $faqCategory));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
