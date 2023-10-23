<?php

namespace Cmat\Ecommerce\Repositories\Caches;

use Cmat\Ecommerce\Repositories\Interfaces\ProductCollectionInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class ProductCollectionCacheDecorator extends CacheAbstractDecorator implements ProductCollectionInterface
{
    public function createSlug($name, $id)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}
