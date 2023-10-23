<?php

namespace Cmat\Theme\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class CustomCssRequest extends Request
{
    public function rules(): array
    {
        return [
            'custom_css' => 'nullable|string',
        ];
    }
}
