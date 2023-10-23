<?php

namespace Cmat\Member\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class RegisterRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => 'required|max:60|min:6|email|unique:members',
            'password' => 'required|min:6',
        ];
    }
}
