<?php

namespace Cmat\Ecommerce\Repositories\Caches;

use Cmat\Ecommerce\Repositories\Interfaces\DiscountInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class DiscountCacheDecorator extends CacheAbstractDecorator implements DiscountInterface
{
    public function getAvailablePromotions(array $with = [], bool $forProductSingle = false)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getProductPriceBasedOnPromotion(array $productIds = [], array $productCollectionIds = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
