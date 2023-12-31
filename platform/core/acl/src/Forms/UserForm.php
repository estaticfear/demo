<?php

namespace Cmat\ACL\Forms;

use Cmat\ACL\Http\Requests\CreateUserRequest;
use Cmat\ACL\Models\User;
use Cmat\ACL\Repositories\Interfaces\RoleInterface;
use Cmat\Base\Forms\FormAbstract;

class UserForm extends FormAbstract
{
    public function __construct(protected RoleInterface $roleRepository)
    {
        parent::__construct();
    }

    public function buildForm(): void
    {
        $roles = $this->roleRepository->pluck('name', 'id');

        $defaultRole = $this->roleRepository->getFirstBy(['is_default' => 1]);

        $this
            ->setupModel(new User())
            ->setValidatorClass(CreateUserRequest::class)
            ->withCustomFields()
            ->add('rowOpen1', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('first_name', 'text', [
                'label' => trans('core/acl::users.info.first_name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'data-counter' => 30,
                ],
                'wrapper' => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
            ])
            ->add('last_name', 'text', [
                'label' => trans('core/acl::users.info.last_name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'data-counter' => 30,
                ],
                'wrapper' => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
            ])
            ->add('rowClose1', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen2', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('username', 'text', [
                'label' => trans('core/acl::users.username'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'data-counter' => 30,
                ],
                'wrapper' => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
            ])
            ->add('email', 'text', [
                'label' => trans('core/acl::users.email'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/acl::users.email_placeholder'),
                    'data-counter' => 60,
                ],
                'wrapper' => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
            ])
            ->add('rowClose2', 'html', [
                'html' => '</div>',
            ])
            ->add('rowOpen3', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('password', 'password', [
                'label' => trans('core/acl::users.password'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'data-counter' => 60,
                ],
                'wrapper' => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
            ])
            ->add('password_confirmation', 'password', [
                'label' => trans('core/acl::users.password_confirmation'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'data-counter' => 60,
                ],
                'wrapper' => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
            ])
            ->add('rowClose3', 'html', [
                'html' => '</div>',
            ])
            ->add('role_id', 'customSelect', [
                'label' => trans('core/acl::users.role'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'class' => 'form-control roles-list',
                ],
                'choices' => ['' => trans('core/acl::users.select_role')] + $roles,
                'default_value' => $defaultRole ? $defaultRole->id : null,
            ])
            ->setBreakFieldPoint('role_id');
    }
}
