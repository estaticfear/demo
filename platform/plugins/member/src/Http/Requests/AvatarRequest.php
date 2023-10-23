<?php

namespace Cmat\Member\Http\Requests;

use Cmat\Support\Http\Requests\Request;
use RvMedia;

class AvatarRequest extends Request
{
    public function rules(): array
    {
        return [
            'avatar_file' => RvMedia::imageValidationRule(),
            'avatar_data' => 'required',
        ];
    }
}
