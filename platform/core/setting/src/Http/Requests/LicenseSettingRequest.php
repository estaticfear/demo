<?php

namespace Cmat\Setting\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class LicenseSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'purchase_code' => 'required',
            'buyer' => 'required|regex:/^[\pL\s\ \_\-0-9]+$/u',
            'license_rules_agreement' => 'accepted:1',
        ];
    }
}
