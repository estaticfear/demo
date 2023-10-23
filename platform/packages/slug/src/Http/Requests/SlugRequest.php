<?php

namespace Cmat\Slug\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class SlugRequest extends Request
{
    public function rules(): array
    {
        return [
            'value' => 'required',
            'slug_id' => 'required',
        ];
    }
}
