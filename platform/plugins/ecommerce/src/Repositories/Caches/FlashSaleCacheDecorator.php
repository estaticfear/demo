<?php

namespace Cmat\Ecommerce\Repositories\Caches;

use Cmat\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class FlashSaleCacheDecorator extends CacheAbstractDecorator implements FlashSaleInterface
{
    public function getAvailableFlashSales(array $with = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
