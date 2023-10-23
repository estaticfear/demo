<?php

namespace Cmat\Base\Facades;

use Cmat\Base\Helpers\BaseHelper;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Base\Helpers\BaseHelper
 */
class BaseHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseHelper::class;
    }
}
