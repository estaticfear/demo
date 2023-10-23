<?php

namespace Cmat\Base\Facades;

use Cmat\Base\Supports\EmailHandler;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Base\Supports\EmailHandler
 */
class EmailHandlerFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return EmailHandler::class;
    }
}
