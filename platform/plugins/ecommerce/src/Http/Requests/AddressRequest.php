<?php

namespace Cmat\Ecommerce\Http\Requests;

use Cmat\Support\Http\Requests\Request;
use EcommerceHelper;

class AddressRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'is_default' => 'integer|min:0|max:1',
        ];

        if (! EcommerceHelper::isUsingInMultipleCountries()) {
            $this->merge(['country' => EcommerceHelper::getFirstCountryId()]);
        }

        return array_merge($rules, EcommerceHelper::getCustomerAddressValidationRules());
    }
}
