<?php

namespace Cmat\Ecommerce\Http\Requests;

use Cmat\Ecommerce\Enums\GlobalOptionEnum;
use Cmat\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ProductOptionRequest extends Request
{
    public function rules(): array
    {
        return [
            'option_name' => 'required',
            'option_type' => [
                Rule::requiredIf(function () {
                    return $this->input('option_type') == GlobalOptionEnum::NA;
                }),
            ],
        ];
    }
}
