<?php

namespace Cmat\Ecommerce\Http\Requests;

use Cmat\Ecommerce\Enums\ShippingRuleTypeEnum;
use Cmat\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ShippingRuleRequest extends Request
{
    public function rules(): array
    {
        $ruleItems = [];

        foreach ($this->input('shipping_rule_items', []) as $key => $item) {
            $ruleItems['shipping_rule_items.' . $key . '.adjustment_price'] = 'required|numeric';
        }

        $ruleItems = [
            'name' => 'required|max:120',
            'from' => 'required|numeric',
            'to' => 'nullable|numeric|min:' . (float)$this->input('from'),
            'price' => 'required',
            'type' => Rule::in(array_keys(ShippingRuleTypeEnum::availableLabels())),
        ] + $ruleItems;

        if (request()->isMethod('POST')) {
            $ruleItems['shipping_id'] = [
                'required',
                Rule::exists('ec_shipping', 'id')->where(function ($query) {
                    if ($this->input('type') == ShippingRuleTypeEnum::BASED_ON_ZIPCODE) {
                        return $query->whereNotNull('country');
                    }
                }),
            ];
        }

        return $ruleItems;
    }

    public function attributes(): array
    {
        $attributes = [];
        foreach ($this->input('shipping_rule_items', []) as $key => $item) {
            $attributes['shipping_rule_items.' . $key . '.adjustment_price'] = trans(
                'plugins/ecommerce::shipping.adjustment_price_of',
                $key
            );
        }

        return $attributes;
    }
}
