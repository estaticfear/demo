<?php

namespace Cmat\Ecommerce\Http\Requests;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class BrandRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'slug' => 'required',
            'order' => 'required|integer|min:0|max:127',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
