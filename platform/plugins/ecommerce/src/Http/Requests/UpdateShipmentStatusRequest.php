<?php

namespace Cmat\Ecommerce\Http\Requests;

use Cmat\Ecommerce\Enums\ShippingStatusEnum;
use Cmat\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UpdateShipmentStatusRequest extends Request
{
    public function rules(): array
    {
        return [
            'status' => 'required|' . Rule::in(ShippingStatusEnum::values()),
        ];
    }
}
