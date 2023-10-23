<?php

namespace Cmat\Table\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class FilterRequest extends Request
{
    public function rules(): array
    {
        return [
            'class' => 'required',
        ];
    }
}
