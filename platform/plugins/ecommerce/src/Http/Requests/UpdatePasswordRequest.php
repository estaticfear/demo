<?php

namespace Cmat\Ecommerce\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class UpdatePasswordRequest extends Request
{
    public function rules(): array
    {
        return [
            'old_password' => 'required|min:6|max:60',
            'password' => 'required|min:6|max:60',
            'password_confirmation' => 'same:password',
        ];
    }
}
