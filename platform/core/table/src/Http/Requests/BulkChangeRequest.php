<?php

namespace Cmat\Table\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class BulkChangeRequest extends Request
{
    public function rules(): array
    {
        return [
            'class' => 'required',
        ];
    }
}
