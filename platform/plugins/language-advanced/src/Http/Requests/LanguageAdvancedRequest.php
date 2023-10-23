<?php

namespace Cmat\LanguageAdvanced\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class LanguageAdvancedRequest extends Request
{
    public function rules(): array
    {
        return [
            'model' => 'required|max:255',
        ];
    }
}
