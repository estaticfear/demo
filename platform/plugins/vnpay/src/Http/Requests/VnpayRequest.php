<?php

namespace Cmat\Vnpay\Http\Requests;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class VnpayRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
