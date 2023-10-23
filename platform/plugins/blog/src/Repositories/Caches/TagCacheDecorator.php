<?php

namespace Cmat\Blog\Repositories\Caches;

use Cmat\Blog\Repositories\Interfaces\TagInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class TagCacheDecorator extends CacheAbstractDecorator implements TagInterface
{
    public function getDataSiteMap()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getPopularTags($limit, array $with = ['slugable'], array $withCount = ['posts'])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getAllTags($active = true)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
