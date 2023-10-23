<?php

namespace Cmat\Ecommerce\Http\Requests;

use BaseHelper;
use Cmat\Payment\Enums\PaymentStatusEnum;
use Cmat\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'customer_id' => 'required|exists:ec_customers,id',
            'customer_address.phone' => BaseHelper::getPhoneValidationRule(),
        ];

        if (is_plugin_active('payment')) {
            $rules['payment_status'] = Rule::in([PaymentStatusEnum::COMPLETED, PaymentStatusEnum::PENDING]);
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'customer_id' => trans('plugins/ecommerce::order.customer_label'),
        ];
    }
}
