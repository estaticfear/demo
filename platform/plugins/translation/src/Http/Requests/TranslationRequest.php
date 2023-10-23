<?php

namespace Cmat\Translation\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class TranslationRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }
}
