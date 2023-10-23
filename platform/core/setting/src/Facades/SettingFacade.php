<?php

namespace Cmat\Setting\Facades;

use Cmat\Setting\Supports\SettingStore;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Setting\Supports\SettingStore
 */
class SettingFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SettingStore::class;
    }
}
