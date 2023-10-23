<?php

namespace Cmat\Ecommerce\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class CartRequest extends Request
{
    public function rules(): array
    {
        return [
            'id' => 'required|min:1',
            'qty' => 'min:1|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => __('Product ID is required'),
        ];
    }
}
