<?php

namespace Cmat\Gallery\Http\Requests;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class GalleryRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|max:120',
            'description' => 'required|max:400',
            'order' => 'required|integer|min:0|max:127',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
