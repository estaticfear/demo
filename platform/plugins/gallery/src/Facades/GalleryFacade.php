<?php

namespace Cmat\Gallery\Facades;

use Cmat\Gallery\GallerySupport;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Gallery\GallerySupport
 */
class GalleryFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return GallerySupport::class;
    }
}
