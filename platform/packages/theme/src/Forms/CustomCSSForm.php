<?php

namespace Cmat\Theme\Forms;

use BaseHelper;
use Cmat\Base\Forms\FormAbstract;
use Cmat\Base\Models\BaseModel;
use Cmat\Theme\Http\Requests\CustomCssRequest;
use Illuminate\Support\Facades\File;
use Theme;

class CustomCSSForm extends FormAbstract
{
    public function buildForm(): void
    {
        $css = null;
        $file = Theme::getStyleIntegrationPath();
        if (File::exists($file)) {
            $css = BaseHelper::getFileData($file, false);
        }

        $this
            ->setupModel(new BaseModel())
            ->setUrl(route('theme.custom-css.post'))
            ->setValidatorClass(CustomCssRequest::class)
            ->add('custom_css', 'textarea', [
                'label' => trans('packages/theme::theme.custom_css'),
                'label_attr' => ['class' => 'control-label'],
                'value' => $css,
                'help_block' => [
                    'text' => trans('packages/theme::theme.custom_css_placeholder'),
                ],
            ]);
    }

    public function getActionButtons(): string
    {
        return view('core/base::forms.partials.form-actions', ['onlySave' => true])->render();
    }
}
