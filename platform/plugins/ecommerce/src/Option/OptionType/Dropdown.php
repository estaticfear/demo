<?php

namespace Cmat\Ecommerce\Option\OptionType;

use Cmat\Ecommerce\Option\Interfaces\OptionTypeInterface;

class Dropdown extends BaseOptionType implements OptionTypeInterface
{
    public function view(): string
    {
        return 'dropdown';
    }
}
