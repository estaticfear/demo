<?php

namespace Cmat\Menu\Http\Requests;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class MenuRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:120',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
