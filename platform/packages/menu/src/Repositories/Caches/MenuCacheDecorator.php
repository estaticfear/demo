<?php

namespace Cmat\Menu\Repositories\Caches;

use Cmat\Menu\Repositories\Interfaces\MenuInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class MenuCacheDecorator extends CacheAbstractDecorator implements MenuInterface
{
    public function findBySlug($slug, $active, array $select = [], array $with = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function createSlug($name)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}
