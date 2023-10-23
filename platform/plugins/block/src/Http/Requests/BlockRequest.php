<?php

namespace Cmat\Block\Http\Requests;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class BlockRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|max:120',
            'alias' => 'required|max:120',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
