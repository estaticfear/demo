<?php

namespace Cmat\Base\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Base\Supports\Action
 */
class ActionFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'core:action';
    }
}
