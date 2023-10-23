<?php

namespace Cmat\Base\Facades;

use Cmat\Base\Supports\MacroableModels;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Base\Supports\MacroableModels
 */
class MacroableModelsFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MacroableModels::class;
    }
}
