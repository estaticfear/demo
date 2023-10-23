<?php

namespace Cmat\ReligiousMerit\Http\Requests;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ReligiousMeritProjectRequest extends Request
{
    public function rules(): array
    {
        return [
            'description' => 'required',
            'content' => 'required',
            'expectation_amount' => 'required',
            'start_date' => 'required',
            'to_date' => 'required',
            'image' => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
