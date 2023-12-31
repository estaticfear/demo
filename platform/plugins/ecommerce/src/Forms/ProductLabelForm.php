<?php

namespace Cmat\Ecommerce\Forms;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Forms\FormAbstract;
use Cmat\Ecommerce\Http\Requests\ProductLabelRequest;
use Cmat\Ecommerce\Models\ProductLabel;

class ProductLabelForm extends FormAbstract
{
    public function buildForm()
    {
        $this
            ->setupModel(new ProductLabel())
            ->setValidatorClass(ProductLabelRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('color', 'customColor', [
                'label' => trans('plugins/ecommerce::product-label.color'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => trans('plugins/ecommerce::product-label.color_placeholder'),
                ],
            ])
            ->add('status', 'customSelect', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control address',
                ],
                'choices' => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
