<?php

namespace Cmat\Ecommerce\Repositories\Caches;

use Cmat\Ecommerce\Repositories\Interfaces\GroupedProductInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class GroupedProductCacheDecorator extends CacheAbstractDecorator implements GroupedProductInterface
{
    public function getChildren($groupedProductId, array $params)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function createGroupedProducts($groupedProductId, array $childItems)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}
