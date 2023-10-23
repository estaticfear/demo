<?php

namespace Cmat\Member\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class UpdatePasswordRequest extends Request
{
    public function rules(): array
    {
        return [
            'password' => 'required|min:6|max:60|confirmed',
        ];
    }
}
