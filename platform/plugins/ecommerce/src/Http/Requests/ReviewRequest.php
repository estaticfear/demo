<?php

namespace Cmat\Ecommerce\Http\Requests;

use EcommerceHelper;
use Cmat\Support\Http\Requests\Request;

class ReviewRequest extends Request
{
    public function rules(): array
    {
        return [
            'product_id' => 'required',
            'star' => 'required|numeric|min:1|max:5',
            'comment' => 'required|max:1000',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:' . EcommerceHelper::reviewMaxFileSize(true),
            'images' => 'array|max:' . EcommerceHelper::reviewMaxFileNumber(),
        ];
    }
}
