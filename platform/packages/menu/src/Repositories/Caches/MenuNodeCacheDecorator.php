<?php

namespace Cmat\Menu\Repositories\Caches;

use Cmat\Menu\Repositories\Interfaces\MenuNodeInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;
use Illuminate\Database\Eloquent\Collection;

class MenuNodeCacheDecorator extends CacheAbstractDecorator implements MenuNodeInterface
{
    public function getByMenuId(int|string $menuId, int|string|null $parentId, array $select = ['*'], array $with = ['child']): Collection
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
