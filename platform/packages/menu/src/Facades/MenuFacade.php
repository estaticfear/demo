<?php

namespace Cmat\Menu\Facades;

use Cmat\Menu\Menu;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Menu\Menu
 */
class MenuFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Menu::class;
    }
}
