<?php

namespace Cmat\Gallery\Repositories\Caches;

use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;
use Cmat\Gallery\Repositories\Interfaces\GalleryInterface;

class GalleryCacheDecorator extends CacheAbstractDecorator implements GalleryInterface
{
    public function getAll(array $with = ['slugable', 'user'])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getDataSiteMap()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getFeaturedGalleries($limit, array $with = ['slugable', 'user'])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
