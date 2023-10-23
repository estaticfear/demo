<?php

namespace Cmat\Ecommerce\Http\Requests;

use Cmat\Ecommerce\Enums\OrderReturnStatusEnum;
use Cmat\Support\Http\Requests\Request;

class UpdateOrderReturnRequest extends Request
{
    public function rules(): array
    {
        return [
            'return_status' => 'required|string|in:' . implode(',', OrderReturnStatusEnum::values()),
        ];
    }
}
