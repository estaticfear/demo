<?php

namespace Cmat\Ecommerce\Option\OptionType;

use Cmat\Ecommerce\Option\Interfaces\OptionTypeInterface;

class Field extends BaseOptionType implements OptionTypeInterface
{
    public function view(): string
    {
        return 'field';
    }
}
