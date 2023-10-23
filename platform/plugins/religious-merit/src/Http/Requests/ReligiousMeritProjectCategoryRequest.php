<?php

namespace Cmat\ReligiousMerit\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class ReligiousMeritProjectCategoryRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }
}
