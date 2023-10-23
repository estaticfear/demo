<?php

namespace Cmat\SimpleSlider\Forms;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Forms\FormAbstract;
use Cmat\SimpleSlider\Http\Requests\SimpleSliderRequest;
use Cmat\SimpleSlider\Models\SimpleSlider;
use Cmat\SimpleSlider\Tables\SimpleSliderItemTable;
use Cmat\Table\TableBuilder;

class SimpleSliderForm extends FormAbstract
{
    public function __construct(protected TableBuilder $tableBuilder)
    {
        parent::__construct();
    }

    public function buildForm(): void
    {
        $this
            ->setupModel(new SimpleSlider())
            ->setValidatorClass(SimpleSliderRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'data-counter' => 120,
                ],
            ])
            ->add('key', 'text', [
                'label' => trans('plugins/simple-slider::simple-slider.key'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'data-counter' => 120,
                ],
            ])
            ->add('description', 'textarea', [
                'label' => trans('core/base::forms.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'rows' => 4,
                    'placeholder' => trans('core/base::forms.description_placeholder'),
                    'data-counter' => 400,
                ],
            ])
            ->add('status', 'customSelect', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices' => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');

        if ($this->model->id) {
            $this->addMetaBoxes([
                'slider-items' => [
                    'title' => trans('plugins/simple-slider::simple-slider.slide_items'),
                    'content' => $this->tableBuilder->create(SimpleSliderItemTable::class)
                        ->setAjaxUrl(route(
                            'simple-slider-item.index',
                            $this->getModel()->id ?: 0
                        ))
                        ->renderTable(),
                ],
            ]);
        }
    }
}
