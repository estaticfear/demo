<?php

namespace Cmat\Ecommerce\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class CreateShipmentRequest extends Request
{
    public function rules(): array
    {
        return [
            'method' => 'required',
        ];
    }
}
