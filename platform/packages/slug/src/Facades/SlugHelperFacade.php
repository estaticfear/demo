<?php

namespace Cmat\Slug\Facades;

use Cmat\Slug\SlugHelper;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Slug\SlugHelper
 */
class SlugHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SlugHelper::class;
    }
}
