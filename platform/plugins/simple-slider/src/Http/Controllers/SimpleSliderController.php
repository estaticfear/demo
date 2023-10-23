<?php

namespace Cmat\SimpleSlider\Http\Controllers;

use Assets;
use Cmat\Base\Events\BeforeEditContentEvent;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Base\Forms\FormBuilder;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Base\Traits\HasDeleteManyItemsTrait;
use Cmat\SimpleSlider\Forms\SimpleSliderForm;
use Cmat\SimpleSlider\Http\Requests\SimpleSliderRequest;
use Cmat\SimpleSlider\Repositories\Interfaces\SimpleSliderInterface;
use Cmat\Base\Http\Controllers\BaseController;
use Cmat\SimpleSlider\Repositories\Interfaces\SimpleSliderItemInterface;
use Illuminate\Http\Request;
use Exception;
use Cmat\SimpleSlider\Tables\SimpleSliderTable;

class SimpleSliderController extends BaseController
{
    use HasDeleteManyItemsTrait;

    public function __construct(
        protected SimpleSliderInterface $simpleSliderRepository,
        protected SimpleSliderItemInterface $simpleSliderItemRepository
    ) {
    }

    public function index(SimpleSliderTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/simple-slider::simple-slider.menu'));

        return $dataTable->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/simple-slider::simple-slider.create'));

        return $formBuilder
            ->create(SimpleSliderForm::class)
            ->removeMetaBox('slider-items')
            ->renderForm();
    }

    public function store(SimpleSliderRequest $request, BaseHttpResponse $response)
    {
        $simpleSlider = $this->simpleSliderRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(SIMPLE_SLIDER_MODULE_SCREEN_NAME, $request, $simpleSlider));

        return $response
            ->setPreviousUrl(route('simple-slider.index'))
            ->setNextUrl(route('simple-slider.edit', $simpleSlider->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        Assets::addScripts(['blockui', 'sortable'])
            ->addScriptsDirectly(['vendor/core/plugins/simple-slider/js/simple-slider-admin.js']);

        $simpleSlider = $this->simpleSliderRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $simpleSlider));

        page_title()->setTitle(trans('core/base::forms.edit_item', ['name' => $simpleSlider->name]));

        return $formBuilder
            ->create(SimpleSliderForm::class, ['model' => $simpleSlider])
            ->renderForm();
    }

    public function update(int|string $id, SimpleSliderRequest $request, BaseHttpResponse $response)
    {
        $simpleSlider = $this->simpleSliderRepository->findOrFail($id);
        $simpleSlider->fill($request->input());

        $this->simpleSliderRepository->createOrUpdate($simpleSlider);

        event(new UpdatedContentEvent(SIMPLE_SLIDER_MODULE_SCREEN_NAME, $request, $simpleSlider));

        return $response
            ->setPreviousUrl(route('simple-slider.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $simpleSlider = $this->simpleSliderRepository->findOrFail($id);
            $this->simpleSliderRepository->delete($simpleSlider);

            event(new DeletedContentEvent(SIMPLE_SLIDER_MODULE_SCREEN_NAME, $request, $simpleSlider));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        return $this->executeDeleteItems($request, $response, $this->simpleSliderRepository, SIMPLE_SLIDER_MODULE_SCREEN_NAME);
    }

    public function postSorting(Request $request, BaseHttpResponse $response)
    {
        foreach ($request->input('items', []) as $key => $id) {
            $this->simpleSliderItemRepository->createOrUpdate(['order' => ($key + 1)], ['id' => $id]);
        }

        return $response->setMessage(trans('plugins/simple-slider::simple-slider.update_slide_position_success'));
    }
}
