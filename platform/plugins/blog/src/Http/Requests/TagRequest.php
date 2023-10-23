<?php

namespace Cmat\Blog\Http\Requests;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class TagRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|max:120',
            'description' => 'max:400',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
