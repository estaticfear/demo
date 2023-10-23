<?php

namespace Cmat\Ecommerce\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class ProductUpdateOrderByRequest extends Request
{
    public function rules(): array
    {
        return [
            'value' => 'required|numeric|min:0|max:100000',
        ];
    }
}
