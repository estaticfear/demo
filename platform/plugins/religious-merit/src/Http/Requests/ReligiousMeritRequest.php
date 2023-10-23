<?php

namespace Cmat\ReligiousMerit\Http\Requests;

use Cmat\ReligiousMerit\Enums\ReligiousMeritStatusEnum;
use Cmat\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ReligiousMeritRequest extends Request
{
    public function rules(): array
    {
        return [
            // 'name' => 'required',
            'status' => Rule::in(ReligiousMeritStatusEnum::values()),
            'amount' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'project_id' => 'required'
        ];
    }
}
