<?php

namespace Cmat\Ecommerce\Forms;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Forms\FormAbstract;
use Cmat\Ecommerce\Http\Requests\BrandRequest;
use Cmat\Ecommerce\Models\Brand;

class BrandForm extends FormAbstract
{
    public function buildForm()
    {
        $this
            ->setupModel(new Brand())
            ->setValidatorClass(BrandRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('description', 'editor', [
                'label' => trans('core/base::forms.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'rows' => 4,
                    'placeholder' => trans('plugins/ecommerce::products.form.description'),
                    'data-counter' => 400,
                ],
            ])
            ->add('website', 'text', [
                'label' => trans('plugins/ecommerce::brands.form.website'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => 'Ex: https://example.com',
                    'data-counter' => 120,
                ],
            ])
            ->add('order', 'number', [
                'label' => trans('core/base::forms.order'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.order_by_placeholder'),
                ],
                'default_value' => 0,
            ])
            ->add('status', 'customSelect', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices' => BaseStatusEnum::labels(),
            ])
            ->add('logo', 'mediaImage', [
                'label' => trans('plugins/ecommerce::brands.logo'),
                'label_attr' => ['class' => 'control-label'],
            ])
            ->add('is_featured', 'onOff', [
                'label' => trans('plugins/ecommerce::brands.form.is_featured'),
                'label_attr' => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->setBreakFieldPoint('status');
    }
}
