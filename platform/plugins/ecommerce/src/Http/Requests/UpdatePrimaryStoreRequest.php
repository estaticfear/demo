<?php

namespace Cmat\Ecommerce\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class UpdatePrimaryStoreRequest extends Request
{
    public function rules(): array
    {
        return [
            'primary_store_id' => 'required|numeric',
        ];
    }
}
