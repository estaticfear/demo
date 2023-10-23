<?php

namespace Cmat\Api\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class VerifyEmailRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|string',
            'token' => 'required',
        ];
    }
}
