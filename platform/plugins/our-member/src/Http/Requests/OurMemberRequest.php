<?php

namespace Cmat\OurMember\Http\Requests;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class OurMemberRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
            'jobtitle' => 'required',
            'avatar' => 'required',
        ];
    }
}
