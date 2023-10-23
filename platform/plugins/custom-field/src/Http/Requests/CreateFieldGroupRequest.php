<?php

namespace Cmat\CustomField\Http\Requests;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CreateFieldGroupRequest extends Request
{
    public function rules(): array
    {
        return [
            'order' => 'integer|min:0|required',
            'rules' => 'json|required',
            'title' => 'required|max:255',
            'status' => ['required', Rule::in(BaseStatusEnum::values())],
        ];
    }
}
