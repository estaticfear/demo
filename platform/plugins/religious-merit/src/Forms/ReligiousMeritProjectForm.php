<?php

namespace Cmat\ReligiousMerit\Forms;

use Assets;
use Cmat\Base\Forms\FormAbstract;
use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Ecommerce\Enums\ProductTypeEnum;
use Cmat\ReligiousMerit\Http\Requests\ReligiousMeritProjectRequest;
use Cmat\ReligiousMerit\Models\ReligiousMeritProject;
use EcommerceHelper;
class ReligiousMeritProjectForm extends FormAbstract
{
    public function buildForm(): void
    {
        $selectedCategory = null;
        if ($this->getModel()) {
            $selectedCategory = $this->getModel()->category();
        }

        $categories = ['' => trans('plugins/religious-merit::religious-merit.select_category')];
        foreach (get_religious_merit_project_categories() as $c) {
            $categories[$c->id] = $c->name;
        }

        Assets::addScripts(['input-mask']);
        Assets::addStylesDirectly(['vendor/core/plugins/ecommerce/css/ecommerce.css'])
            ->addScriptsDirectly([
                'vendor/core/plugins/ecommerce/js/select-products.js',
            ])
            ->addScripts(['blockui', 'input-mask'])
            ->usingVueJS();

        if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation()) {
            Assets::addScriptsDirectly('vendor/core/plugins/location/js/location.js');
        }

        // dd($this->getModel()->products->toArray());
        $this
            ->setFormOption('template', 'core/base::forms.form-tabs')
            ->setupModel(new ReligiousMeritProject())
            ->setValidatorClass(ReligiousMeritProjectRequest::class)
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
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'rows' => 4,
                    'placeholder' => trans('core/base::forms.description_placeholder'),
                    'data-counter' => 400,
                ],
            ])
            ->add('transaction_message_prefix', 'text', [
                'label' => trans('plugins/religious-merit::religious-merit.transaction_message_prefix'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => trans('plugins/religious-merit::religious-merit.transaction_message_prefix'),
                    'data-counter' => 50,
                ],
            ])
            ->add('is_featured', 'onOff', [
                'label' => trans('core/base::forms.is_featured'),
                'label_attr' => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->add('content', 'editor', [
                'label' => trans('core/base::forms.content'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'rows' => 6,
                    'placeholder' => trans('core/base::forms.description_placeholder'),
                    'with-short-code' => true,
                ],
            ])
            ->addMetaBoxes([
                'general' => [
                    'title' => __('Hiện vật'),
                    'content' => view(
                        'plugins/religious-merit::forms.partials.project-products', [
                            'project_id' => $this->getModel()->id,
                            'product_type' => ProductTypeEnum::PHYSICAL
                        ]
                    )->render(),
                ]])
            ->addMetaBoxes([
                'general' => [
                    'title' => __('Hiện vật/công sức'),
                    'content' => view(
                        'plugins/religious-merit::forms.partials.project-products', [
                            'project_id' => $this->getModel()->id,
                        ]
                    )->render(),
                ]])
            ->add('status', 'customSelect', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => BaseStatusEnum::labels(),
            ])
            ->add('project_category_id', 'customSelect', [
                'label' => trans('plugins/religious-merit::religious-merit.category'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => $categories,
                'value' => old('category', $selectedCategory),
            ])
            ->add('image', 'mediaImage', [
                'label' => trans('core/base::forms.image'),
                'label_attr' => ['class' => 'control-label required'],
            ])
            ->add('expectation_amount', 'text', [
                'label'      => trans('plugins/religious-merit::religious-merit-project.expectation-amount'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control input-mask-number',
                ],
            ])
            ->add('start_date', 'datePicker', [
                'label' => trans('plugins/religious-merit::religious-merit-project.start-date'),
                'label_attr' => ['class' => 'control-label required'],
                'default_value' => false,
            ])
            ->add('to_date', 'datePicker', [
                'label' => trans('plugins/religious-merit::religious-merit-project.to-date'),
                'label_attr' => ['class' => 'control-label required'],
                'default_value' => false,
            ])
            ->add('can_contribute_effort', 'onOff', [
                'label' => trans('plugins/religious-merit::religious-merit-project.can-contribute-effort'),
                'label_attr' => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->add('can_contribute_artifact', 'onOff', [
                'label' => trans('plugins/religious-merit::religious-merit-project.can-contribute-artifact'),
                'label_attr' => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->setBreakFieldPoint('status');
    }
}
