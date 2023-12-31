<?php

namespace Cmat\Ecommerce\Http\Controllers;

use Cmat\Base\Events\BeforeEditContentEvent;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Base\Forms\FormBuilder;
use Cmat\Base\Http\Controllers\BaseController;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Ecommerce\Forms\GlobalOptionForm;
use Cmat\Ecommerce\Http\Requests\GlobalOptionRequest;
use Cmat\Ecommerce\Repositories\Interfaces\GlobalOptionInterface;
use Cmat\Ecommerce\Tables\GlobalOptionTable;
use Exception;
use Illuminate\Http\Request;

class ProductOptionController extends BaseController
{
    public function __construct(protected GlobalOptionInterface $globalOptionRepository)
    {
    }

    public function index(GlobalOptionTable $table)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-option.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-option.create'));

        return $formBuilder->create(GlobalOptionForm::class)->renderForm();
    }

    public function store(GlobalOptionRequest $request, BaseHttpResponse $response)
    {
        $option = $this->globalOptionRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(GLOBAL_OPTION_MODULE_SCREEN_NAME, $request, $option));

        return $response
            ->setPreviousUrl(route('global-option.index'))
            ->setNextUrl(route('global-option.edit', $option->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $option = $this->globalOptionRepository->findOrFail($id, ['values']);

        event(new BeforeEditContentEvent($request, $option));

        page_title()->setTitle(trans('plugins/ecommerce::product-option.edit', ['name' => $option->name]));

        return $formBuilder->create(GlobalOptionForm::class, ['model' => $option])->renderForm();
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $option = $this->globalOptionRepository->findOrFail($id);

            $this->globalOptionRepository->delete($option);

            event(new DeletedContentEvent(GLOBAL_OPTION_MODULE_SCREEN_NAME, $request, $option));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function update(int|string $id, GlobalOptionRequest $request, BaseHttpResponse $response)
    {
        $option = $this->globalOptionRepository->findOrFail($id);

        $this->globalOptionRepository->createOrUpdate($request->input(), ['id' => $id]);

        event(new UpdatedContentEvent(GLOBAL_OPTION_MODULE_SCREEN_NAME, $request, $option));

        return $response
            ->setPreviousUrl(route('global-option.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
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
            $option = $this->globalOptionRepository->findOrFail($id);
            $this->globalOptionRepository->delete($option);
            event(new DeletedContentEvent(GLOBAL_OPTION_MODULE_SCREEN_NAME, $request, $option));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function ajaxInfo(Request $request, BaseHttpResponse $response): BaseHttpResponse
    {
        $optionsValues = $this->globalOptionRepository->findOrFail($request->input('id'), ['values']);

        return $response->setData($optionsValues);
    }
}
