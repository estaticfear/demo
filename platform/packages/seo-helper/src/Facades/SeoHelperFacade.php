<?php

namespace Cmat\SeoHelper\Facades;

use Cmat\SeoHelper\SeoHelper;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\SeoHelper\SeoHelper
 * @since 02/12/2015 14:08 PM
 */
class SeoHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SeoHelper::class;
    }
}
