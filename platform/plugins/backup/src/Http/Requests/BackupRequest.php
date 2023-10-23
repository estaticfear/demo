<?php

namespace Cmat\Backup\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class BackupRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }
}
