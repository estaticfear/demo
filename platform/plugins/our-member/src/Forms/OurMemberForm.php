<?php

namespace Cmat\OurMember\Forms;

use Cmat\Base\Forms\FormAbstract;
use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\OurMember\Http\Requests\OurMemberRequest;
use Cmat\OurMember\Models\OurMember;

class OurMemberForm extends FormAbstract
{
    public function buildForm(): void
    {
        $this
            ->setupModel(new OurMember)
            ->setValidatorClass(OurMemberRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('plugins/our-member::our-member.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'data-counter' => 120,
                ],
            ])
            ->add('jobtitle', 'text', [
                'label' => trans('plugins/our-member::our-member.jobtitle'),
                'label_attr' => ['class' => 'control-label required'],
            ])
            ->add('introduct', 'textarea', [
                'label' => trans('plugins/our-member::our-member.introduct'),
                'label_attr' => ['class' => 'control-label'],
            ])
            ->add('status', 'customSelect', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => BaseStatusEnum::labels(),
            ])
            ->add('avatar', 'mediaImage', [
                'label' => trans('plugins/our-member::our-member.avatar'),
                'label_attr' => ['class' => 'control-label required'],
            ])
            ->setBreakFieldPoint('status');
    }
}
