<?php

namespace Cmat\Ecommerce\Enums;

use Cmat\Base\Supports\Enum;
use Cmat\Ecommerce\Option\OptionType\Checkbox;
use Cmat\Ecommerce\Option\OptionType\Dropdown;
use Cmat\Ecommerce\Option\OptionType\Field;
use Cmat\Ecommerce\Option\OptionType\RadioButton;

class GlobalOptionEnum extends Enum
{
    public const NA = 'N/A';

    public const FIELD = Field::class;

    public const TYPE_PERCENT = 1;

    public static function options(): array
    {
        return [
            'N/A' => trans('plugins/ecommerce::product-option.please_select_option'),
            'Text' => [
                Field::class => 'Field',
            ],
            'Select' => [
                Dropdown::class => 'Dropdown',
                Checkbox::class => 'Checkbox',
                RadioButton::class => 'RadioButton',
            ],
        ];
    }
}
