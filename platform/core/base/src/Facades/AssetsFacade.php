<?php

namespace Cmat\Base\Facades;

use Cmat\Base\Supports\Assets;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Base\Supports\Assets
 */
class AssetsFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Assets::class;
    }
}
