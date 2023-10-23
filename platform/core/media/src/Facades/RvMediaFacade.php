<?php

namespace Cmat\Media\Facades;

use Cmat\Media\RvMedia;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Media\RvMedia
 */
class RvMediaFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RvMedia::class;
    }
}
