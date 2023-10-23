<?php

namespace Cmat\SocialLogin\Facades;

use Cmat\SocialLogin\Supports\SocialService;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\SocialLogin\Supports\SocialService
 */
class SocialServiceFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SocialService::class;
    }
}
