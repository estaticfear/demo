<?php

namespace Cmat\Api\Facades;

use Cmat\Api\Supports\ApiHelper;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Api\Supports\ApiHelper
 */
class ApiHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ApiHelper::class;
    }
}
