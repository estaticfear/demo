<?php

namespace Cmat\Contact\Http\Requests;

use Cmat\Contact\Enums\ContactStatusEnum;
use Cmat\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class EditContactRequest extends Request
{
    public function rules(): array
    {
        return [
            'status' => Rule::in(ContactStatusEnum::values()),
        ];
    }
}
