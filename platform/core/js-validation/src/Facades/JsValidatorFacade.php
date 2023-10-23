<?php

namespace Cmat\JsValidation\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\JsValidation\JsValidatorFactory
 */
class JsValidatorFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'js-validator';
    }
}
