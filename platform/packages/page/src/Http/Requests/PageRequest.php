<?php

namespace Cmat\Page\Http\Requests;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Page\Supports\Template;
use Cmat\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PageRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|max:120',
            'description' => 'max:400',
            'template' => Rule::in(array_keys(Template::getPageTemplates())),
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
