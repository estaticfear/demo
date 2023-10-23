<?php

namespace Cmat\Ecommerce\Facades;

use Cmat\Ecommerce\Supports\DiscountSupport;
use Illuminate\Support\Facades\Facade;

class DiscountFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return DiscountSupport::class;
    }
}
