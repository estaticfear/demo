<?php

namespace Cmat\Contact\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class ContactReplyRequest extends Request
{
    public function rules(): array
    {
        return [
            'message' => 'required',
        ];
    }
}
