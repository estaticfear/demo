<?php

namespace Cmat\Ecommerce\Repositories\Caches;

use Cmat\Ecommerce\Repositories\Interfaces\BrandInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class BrandCacheDecorator extends CacheAbstractDecorator implements BrandInterface
{
    public function getAll(array $condition = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
