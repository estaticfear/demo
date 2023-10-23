<?php

namespace Cmat\Ecommerce\Facades;

use Cmat\Ecommerce\Supports\ProductCategoryHelper;
use Illuminate\Support\Facades\Facade;

class ProductCategoryHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ProductCategoryHelper::class;
    }
}
