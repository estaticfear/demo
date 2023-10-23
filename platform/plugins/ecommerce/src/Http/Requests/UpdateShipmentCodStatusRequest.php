<?php

namespace Cmat\Ecommerce\Http\Requests;

use Cmat\Ecommerce\Enums\ShippingCodStatusEnum;
use Cmat\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UpdateShipmentCodStatusRequest extends Request
{
    public function rules(): array
    {
        return [
            'status' => 'required|' . Rule::in(ShippingCodStatusEnum::values()),
        ];
    }
}
