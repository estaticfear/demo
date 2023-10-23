<?php

namespace Cmat\Ecommerce\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class RefundRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'refund_amount' => 'numeric|min:0',
        ];

        foreach ($this->input('products', []) as $key => $item) {
            $rules[] = [
                'products.' . $key => 'numeric|min:0',
            ];
        }

        return $rules;
    }
}
