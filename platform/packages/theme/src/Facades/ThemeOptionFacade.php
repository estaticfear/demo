<?php

namespace Cmat\Theme\Facades;

use Cmat\Theme\ThemeOption;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Theme\ThemeOption
 */
class ThemeOptionFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ThemeOption::class;
    }
}
