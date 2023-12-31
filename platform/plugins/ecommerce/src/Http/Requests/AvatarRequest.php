<?php

namespace Cmat\Ecommerce\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class AvatarRequest extends Request
{
    public function rules(): array
    {
        return [
            'avatar_file' => 'required|image|mimes:jpg,jpeg,png',
            'avatar_data' => 'required',
        ];
    }
}
