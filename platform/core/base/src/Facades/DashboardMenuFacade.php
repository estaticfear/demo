<?php

namespace Cmat\Base\Facades;

use Cmat\Base\Supports\DashboardMenu;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Base\Supports\DashboardMenu
 */
class DashboardMenuFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return DashboardMenu::class;
    }
}
