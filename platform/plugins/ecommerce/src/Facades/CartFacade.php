<?php

namespace Cmat\Ecommerce\Facades;

use Cmat\Ecommerce\Cart\Cart;
use Illuminate\Support\Facades\Facade;

class CartFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Cart::class;
    }
}
