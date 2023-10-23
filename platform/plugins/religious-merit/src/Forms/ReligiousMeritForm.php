<?php

namespace Cmat\ReligiousMerit\Forms;

use Assets;
use Cmat\Base\Forms\FormAbstract;
use Cmat\Ecommerce\Enums\ProductTypeEnum;
use Cmat\ReligiousMerit\Enums\PaymentGateTypeEnum;
use Cmat\ReligiousMerit\Enums\ReligiousMeritStatusEnum;
use Cmat\ReligiousMerit\Enums\ReligiousTypeEnum;
use Cmat\ReligiousMerit\Http\Requests\ReligiousMeritRequest;
use Cmat\ReligiousMerit\Models\ReligiousMerit;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProjectInterface;
use Html;
use BaseHelper;

class ReligiousMeritForm extends FormAbstract
{
    public function __construct(
        protected ReligiousMeritProjectInterface $religiousMeritProjectRepository
    )
    {
        parent::__construct();
    }

    public function buildForm(): void
    {
        Assets::addScriptsDirectly([
            'vendor/core/plugins/religious-merit/js/rm-admin.js']);

        $order = $this->getModel();
        $listProduct = '';

        $isOrderArtifactOrEffort = $order && in_array($order->type, [ReligiousTypeEnum::ARTIFACT, ReligiousTypeEnum::EFFORT]);

        if ($isOrderArtifactOrEffort) {
            $listProduct = '<table class="table table-striped table-hover vertical-middle dataTable no-footer dtr-inline collapsed">';
            $listProduct .= '<tr><th>Tên</th><th>Loại</th><th>Số lượng</th></tr>';
                $product_type = $order->type == ReligiousTypeEnum::ARTIFACT ? ProductTypeEnum::PHYSICAL : ProductTypeEnum::DIGITAL;
                $projectProducts = $order->meritProducts($product_type)->get();
                foreach($projectProducts as $p) {
                    $name = $p->meritProjectProduct->product_name;
                    if (!empty($p->meritProjectProduct->product->variation_attributes)) {
                        $name .= ' ' . $p->meritProjectProduct->product->variation_attributes;
                    }
                    $listProduct .= '<tr>';
                    // $listProduct .= '<td>' . Html::link(route('products.edit', $p->meritProjectProduct->product_id), BaseHelper::clean($p->meritProjectProduct->product_name)) . '</td>';
                    $listProduct .= '<td>' . $name . '</td>';
                    $listProduct .= '<td>' . trans('plugins/religious-merit::religious-merit.product_types.' . $p->meritProjectProduct->product_type) . '</td>';
                    $listProduct .= '<td>' . $p->qty . '</td>';
                    $listProduct .= '</tr>';
                }
            $listProduct .= '</table>';
            $listProduct .= '<div class="my-3" id="add-product-modal" data-meritId="' . $order->id . '" data-projectId="' . $order->project->id . '">Cập nhật công đức/hiện vật</div>';
        }

        Assets::addScripts(['input-mask']);

        $formBuilder = $this
            ->setupModel(new ReligiousMerit())
            ->setValidatorClass(ReligiousMeritRequest::class)
            ->withCustomFields();

        if (empty($order->id)) {
            $projectChoices = ['' => 'N/A'];
            if (!empty($order->project_id)) {
                $projectChoices[$order->project_id] = $order->project->name;
            }
            $formBuilder->add('project_id', 'autocomplete', [
                'label' => trans('plugins/religious-merit::religious-merit.project'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'id' => 'project_id',
                    'data-url' => route('religious-merit.search-projects'),
                ],
                'choices' => $projectChoices
            ]);
        } else {
            if ($order->project) {
                $formBuilder->add('html-project-name-wrapper', 'html', [
                    'html' => '<h6>Dự án</h6>'
                ]);
                $formBuilder->add('html-project-name', 'html', [
                    'html' => '<p>' . Html::link(route('religious-merit-project.edit', $order->project->id), BaseHelper::clean($order->project->name)) . '</p>'
                ]);
                $formBuilder->add('project_id', 'hidden');
            }
        }

        $memberChoices = ['0' => 'N/A'];
        if (!empty($order->member_id)) {
            $member = $order->member;
            $memberChoices[$member->id] = $member->email;
        }
        $formBuilder->add('member_id', 'autocomplete', [
            'label' => trans('plugins/religious-merit::religious-merit.member'),
            'label_attr' => ['class' => 'control-label'],
            'attr' => [
                'id' => 'member_id',
                'data-url' => route('religious-merit.search-members'),
            ],
            'choices' => $memberChoices
        ]);

        $formBuilder->add('name', 'text', [
            'label' => trans('core/base::forms.name'),
            'label_attr' => ['class' => 'control-label'],
            'attr' => [
                'placeholder' => trans('core/base::forms.name_placeholder'),
                'data-counter' => 120,
            ],
        ])
            ->add('phone_number', 'text', [
                'label' => trans('plugins/religious-merit::religious-merit.phone_number'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => trans('plugins/religious-merit::religious-merit.phone_number'),
                ],
            ])
            ->add('address', 'text', [
                'label' => trans('plugins/religious-merit::religious-merit.address'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => trans('plugins/religious-merit::religious-merit.address'),
                ],
            ])
            ->add('is_hidden', 'onOff', [
                'label' => trans('plugins/religious-merit::religious-merit.is_hidden'),
                'label_attr' => ['class' => 'control-label'],
                'default_value' => false,
            ]);

        $amount_attr = [
            'class' => 'form-control input-mask-number',
        ];
        if (!empty($order->id) && $order->payment_gate != PaymentGateTypeEnum::TRANSFER() && $order->payment_gate != PaymentGateTypeEnum::CASH()) {
            $amount_attr['disabled'] = 'disabled';
        }

        if (empty($order->id)) {
            $amount_attr['value'] = 0;
        }

        $formBuilder->add('amount', 'text', [
            'label' => trans('plugins/religious-merit::religious-merit.amount'),
            'label_attr' => ['class' => 'control-label required'],
            'attr' => $amount_attr,
            'default_value' => 0
        ]);

        $formBuilder->add('transaction_message', 'text', [
            'label' => trans('plugins/religious-merit::religious-merit.transaction_message'),
            'label_attr' => ['class' => 'control-label'],
            'attr' => [
                'placeholder' => trans('plugins/religious-merit::religious-merit.transaction_message'),
                'data-counter' => 50,
            ],
        ]);

        $formBuilder->add('transaction_image_url', 'mediaImage', [
            'label' => trans('plugins/religious-merit::religious-merit.transaction-image'),
            'label_attr' => ['class' => 'control-label'],
        ]);

        if ($listProduct) {
            $formBuilder->add('danh-sach-vat-pham-wrapper', 'html', [
                'html' => '<h6>Danh sách hiện vật/ngày công</h6>'
            ]);
            $formBuilder->add('danh-sach-vat-pham', 'html', [
                'html' => $listProduct
            ]);
        }

        $formBuilder->add('status', 'customSelect', [
            'label' => trans('plugins/religious-merit::religious-merit.status'),
            'label_attr' => ['class' => 'control-label'],
            'attr' => [
                'class' => 'form-control select-full',
            ],
            'choices' => ReligiousMeritStatusEnum::labels(),
        ]);

        if (!empty($order->id)) {
            $formBuilder->add('type', 'static', [
                'label' => trans('plugins/religious-merit::religious-merit.type'),
                'tag' => 'div',
                'attr' => ['class' => 'form-control-static', 'style' => 'text-transform: capitalize'],
                'value' => trans('plugins/religious-merit::religious-merit.types.' . $order->type)
            ]);
        }

        if (empty($order->id)) {
                $formBuilder
                    ->add('payment_gate', 'customSelect', [
                        'label' => trans('plugins/religious-merit::religious-merit.payment_gate'),
                        'label_attr' => ['class' => 'control-label required'],
                        'attr' => [
                            'class' => 'form-control',
                        ],
                        'choices' => [
                            PaymentGateTypeEnum::TRANSFER()->getValue() => PaymentGateTypeEnum::TRANSFER()->label(),
                            PaymentGateTypeEnum::CASH()->getValue() => PaymentGateTypeEnum::CASH()->label(),
                        ],
                    ]);
        } else {
            if ($order->type == ReligiousTypeEnum::MONEY) {
                $formBuilder
                    ->add('payment_gate', 'customSelect', [
                        'label' => trans('plugins/religious-merit::religious-merit.payment_gate'),
                        'label_attr' => ['class' => 'control-label'],
                        'attr' => [
                            'disabled' => 'disabled',
                            'class' => 'form-control readonly',
                        ],
                        'choices' => PaymentGateTypeEnum::labels(),
                    ]);
                }
        }

        $formBuilder->setBreakFieldPoint('status');
    }
}
