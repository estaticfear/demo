<?php

namespace Cmat\Ecommerce\Repositories\Caches;

use Cmat\Ecommerce\Repositories\Interfaces\ProductAttributeSetInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class ProductAttributeSetCacheDecorator extends CacheAbstractDecorator implements ProductAttributeSetInterface
{
    public function getByProductId($productId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getAllWithSelected($productId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
