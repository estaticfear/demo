<?php

namespace Cmat\Base\Facades;

use Cmat\Base\Supports\MetaBox;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Base\Supports\MetaBox
 */
class MetaBoxFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MetaBox::class;
    }
}
