<?php

namespace Cmat\ReligiousMerit\Http\Requests;

use Cmat\ReligiousMerit\Enums\PaymentGateTypeEnum;
use Cmat\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PublicReligiousMeritRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'payment_gate' => Rule::in(PaymentGateTypeEnum::values()),
            'project_id' => 'required|integer',
            'name' => 'required|min:1',
            'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'address' => 'max:255',
            'email' => 'nullable|email',
            // 'amount' => 'required|integer|min:1',

        ];

        if (is_plugin_active('captcha')) {
            if (setting('enable_captcha')) {
                $rules += [
                    'g-recaptcha-response' => 'required|captcha',
                ];
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'g-recaptcha-response.required' => trans('plugins/religious-merit::religious-merit.captcha_verification_failed'),
            'g-recaptcha-response.captcha' => trans('plugins/religious-merit::religious-merit.captcha_verification_failed'),
        ];
    }

    public function attributes()
    {
        return [
            'name' => mb_strtolower(trans('plugins/religious-merit::religious-merit.fullname')),
            'amount' => mb_strtolower(trans('plugins/religious-merit::religious-merit.merit_amount')),
        ];
    }
}
