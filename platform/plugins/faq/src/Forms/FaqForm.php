<?php

namespace Cmat\Faq\Forms;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Forms\FormAbstract;
use Cmat\Faq\Http\Requests\FaqRequest;
use Cmat\Faq\Models\Faq;
use Cmat\Faq\Repositories\Interfaces\FaqCategoryInterface;

class FaqForm extends FormAbstract
{
    public function buildForm(): void
    {
        $this
            ->setupModel(new Faq())
            ->setValidatorClass(FaqRequest::class)
            ->withCustomFields()
            ->add('category_id', 'customSelect', [
                'label' => trans('plugins/faq::faq.category'),
                'label_attr' => ['class' => 'control-label required'],
                'choices' => ['' => trans('plugins/faq::faq.select_category')] + app(FaqCategoryInterface::class)->pluck('name', 'id'),
            ])
            ->add('question', 'text', [
                'label' => trans('plugins/faq::faq.question'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'rows' => 4,
                ],
            ])
            ->add('answer', 'editor', [
                'label' => trans('plugins/faq::faq.answer'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'rows' => 4,
                ],
            ])
            ->add('status', 'customSelect', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices' => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
