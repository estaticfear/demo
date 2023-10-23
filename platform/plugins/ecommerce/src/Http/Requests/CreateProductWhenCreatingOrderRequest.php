<?php

namespace Cmat\Ecommerce\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class CreateProductWhenCreatingOrderRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'price' => 'numeric|nullable',
        ];
    }
}
