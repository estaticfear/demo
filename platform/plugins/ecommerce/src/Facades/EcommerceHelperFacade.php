<?php

namespace Cmat\Ecommerce\Facades;

use Cmat\Ecommerce\Supports\EcommerceHelper;
use Illuminate\Support\Facades\Facade;

class EcommerceHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return EcommerceHelper::class;
    }
}
