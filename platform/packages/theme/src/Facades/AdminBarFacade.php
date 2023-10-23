<?php

namespace Cmat\Theme\Facades;

use Cmat\Theme\Supports\AdminBar;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Theme\Supports\AdminBar
 */
class AdminBarFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AdminBar::class;
    }
}
