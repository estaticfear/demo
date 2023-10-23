<?php

namespace Cmat\Language\Facades;

use Cmat\Language\LanguageManager;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Language\LanguageManager
 */
class LanguageFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LanguageManager::class;
    }
}
