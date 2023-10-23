<?php

namespace Cmat\ReligiousMerit\Forms;

use Assets;
use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Forms\FormAbstract;
use Cmat\ReligiousMerit\Http\Requests\ReligiousMeritProjectCategoryRequest;
use Cmat\ReligiousMerit\Models\ReligiousMeritProjectCategory;

class ReligiousMeritProjectCategoryForm extends FormAbstract
{
    public function buildForm(): void
    {
        Assets::addScripts(['input-mask']);

        $this
            ->setupModel(new ReligiousMeritProjectCategory())
            ->setValidatorClass(ReligiousMeritProjectCategoryRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('description', 'textarea', [
                'label' => trans('core/base::forms.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'rows' => 4,
                    'placeholder' => trans('core/base::forms.description_placeholder'),
                    'data-counter' => 255,
                ],
            ])
            ->add('content', 'editor', [
                'label' => trans('core/base::forms.content'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'rows' => 8,
                    'placeholder' => trans('core/base::forms.content_placeholder'),
                    'with-short-code' => true,
                ],
            ])
            ->add('status', 'customSelect', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => BaseStatusEnum::labels(),
            ])
            ->add('order', 'number', [
                'label' => trans('core/base::forms.order'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.order_by_placeholder'),
                ],
                'default_value' => 0,
            ])
            ->add('image', 'mediaImage', [
                'label' => trans('core/base::forms.image'),
                'label_attr' => ['class' => 'control-label required'],
            ])
            ->setBreakFieldPoint('status');
    }
}
