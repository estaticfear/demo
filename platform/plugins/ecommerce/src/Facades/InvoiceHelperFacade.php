<?php

namespace Cmat\Ecommerce\Facades;

use Cmat\Ecommerce\Supports\InvoiceHelper;
use Illuminate\Support\Facades\Facade;

class InvoiceHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return InvoiceHelper::class;
    }
}
