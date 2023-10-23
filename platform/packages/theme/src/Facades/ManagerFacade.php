<?php

namespace Cmat\Theme\Facades;

use Cmat\Theme\Manager;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Theme\Manager
 */
class ManagerFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Manager::class;
    }
}
