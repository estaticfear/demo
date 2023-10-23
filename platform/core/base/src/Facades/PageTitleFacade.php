<?php

namespace Cmat\Base\Facades;

use Cmat\Base\Supports\PageTitle;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Base\Supports\PageTitle
 */
class PageTitleFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PageTitle::class;
    }
}
