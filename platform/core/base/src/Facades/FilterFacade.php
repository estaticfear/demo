<?php

namespace Cmat\Base\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Base\Supports\Filter
 */
class FilterFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'core:filter';
    }
}
