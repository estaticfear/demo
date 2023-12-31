<?php

namespace Cmat\Faq\Http\Controllers;

use Cmat\Base\Events\BeforeEditContentEvent;
use Cmat\Base\Traits\HasDeleteManyItemsTrait;
use Cmat\Faq\Http\Requests\FaqRequest;
use Cmat\Faq\Repositories\Interfaces\FaqInterface;
use Cmat\Base\Http\Controllers\BaseController;
use Exception;
use Illuminate\Http\Request;
use Cmat\Faq\Tables\FaqTable;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Faq\Forms\FaqForm;
use Cmat\Base\Forms\FormBuilder;

class FaqController extends BaseController
{
    use HasDeleteManyItemsTrait;

    public function __construct(protected FaqInterface $faqRepository)
    {
    }

    public function index(FaqTable $table)
    {
        page_title()->setTitle(trans('plugins/faq::faq.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/faq::faq.create'));

        return $formBuilder->create(FaqForm::class)->renderForm();
    }

    public function store(FaqRequest $request, BaseHttpResponse $response)
    {
        $faq = $this->faqRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(FAQ_MODULE_SCREEN_NAME, $request, $faq));

        return $response
            ->setPreviousUrl(route('faq.index'))
            ->setNextUrl(route('faq.edit', $faq->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $faq = $this->faqRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $faq));

        page_title()->setTitle(trans('core/base::forms.edit_item', ['name' => $faq->question]));

        return $formBuilder->create(FaqForm::class, ['model' => $faq])->renderForm();
    }

    public function update(int|string $id, FaqRequest $request, BaseHttpResponse $response)
    {
        $faq = $this->faqRepository->findOrFail($id);

        $faq->fill($request->input());

        $this->faqRepository->createOrUpdate($faq);

        event(new UpdatedContentEvent(FAQ_MODULE_SCREEN_NAME, $request, $faq));

        return $response
            ->setPreviousUrl(route('faq.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $faq = $this->faqRepository->findOrFail($id);

            $this->faqRepository->delete($faq);

            event(new DeletedContentEvent(FAQ_MODULE_SCREEN_NAME, $request, $faq));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        return $this->executeDeleteItems($request, $response, $this->faqRepository, FAQ_MODULE_SCREEN_NAME);
    }
}
