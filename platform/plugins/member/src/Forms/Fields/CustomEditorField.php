<?php

namespace Cmat\Member\Forms\Fields;

use BaseHelper;
use Cmat\Base\Supports\Editor;
use Illuminate\Support\Arr;
use Kris\LaravelFormBuilder\Fields\FormField;

class CustomEditorField extends FormField
{
    protected function getTemplate(): string
    {
        return 'plugins/member::forms.fields.custom-editor';
    }

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true): string
    {
        (new Editor())->registerAssets();

        $options['attr'] = Arr::set($options['attr'], 'class', Arr::get($options['attr'], 'class') . 'form-control editor-' . BaseHelper::getRichEditor());

        return parent::render($options, $showLabel, $showField, $showError);
    }
}
