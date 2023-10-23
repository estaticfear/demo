<?php

namespace Cmat\Ecommerce\Repositories\Caches;

use Cmat\Ecommerce\Repositories\Interfaces\ReviewInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class ReviewCacheDecorator extends CacheAbstractDecorator implements ReviewInterface
{
    public function getGroupedByProductId($productId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
