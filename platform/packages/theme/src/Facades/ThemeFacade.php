<?php

namespace Cmat\Theme\Facades;

use Cmat\Theme\Theme;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Theme\Theme
 */
class ThemeFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Theme::class;
    }
}
