<?php

namespace Cmat\Theme\Facades;

use Cmat\Theme\Supports\SiteMapManager;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Theme\Supports\SiteMapManager
 */
class SiteMapManagerFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SiteMapManager::class;
    }
}
