<?php

namespace Cmat\Ecommerce\Option\OptionType;

use Cmat\Ecommerce\Option\Interfaces\OptionTypeInterface;

class RadioButton extends BaseOptionType implements OptionTypeInterface
{
    public function view(): string
    {
        return 'radio';
    }
}
