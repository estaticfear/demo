<?php

namespace Cmat\Ecommerce\Facades;

use Cmat\Ecommerce\Supports\FlashSaleSupport;
use Illuminate\Support\Facades\Facade;

class FlashSaleFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FlashSaleSupport::class;
    }
}
