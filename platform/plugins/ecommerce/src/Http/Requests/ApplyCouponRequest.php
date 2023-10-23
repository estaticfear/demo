<?php

namespace Cmat\Ecommerce\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class ApplyCouponRequest extends Request
{
    public function rules(): array
    {
        return [
            'coupon_code' => 'required|max:255',
        ];
    }
}
