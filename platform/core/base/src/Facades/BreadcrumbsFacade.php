<?php

namespace Cmat\Base\Facades;

use Cmat\Base\Supports\BreadcrumbsManager;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Base\Supports\BreadcrumbsManager
 */
class BreadcrumbsFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BreadcrumbsManager::class;
    }
}
