<?php

namespace Cmat\Captcha\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Captcha\Captcha
 */
class CaptchaFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'captcha';
    }
}
