<?php

namespace Cmat\Ecommerce\Http\Requests;

use BaseHelper;
use Cmat\Support\Http\Requests\Request;

class StoreLocatorRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'email' => 'email|nullable|max:60',
            'phone' => 'required|' . BaseHelper::getPhoneValidationRule(),
            'state' => 'required|max:120',
            'city' => 'required|max:120',
            'address' => 'required|max:120',
        ];
    }
}
