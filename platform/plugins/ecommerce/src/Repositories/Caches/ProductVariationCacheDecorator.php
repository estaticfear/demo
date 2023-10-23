<?php

namespace Cmat\Ecommerce\Repositories\Caches;

use Cmat\Ecommerce\Repositories\Interfaces\ProductVariationInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class ProductVariationCacheDecorator extends CacheAbstractDecorator implements ProductVariationInterface
{
    public function getVariationByAttributes($configurableProductId, array $attributes)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getVariationByAttributesOrCreate($configurableProductId, array $attributes)
    {
        if ($this->getVariationByAttributes($configurableProductId, $attributes)) {
            return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
        }

        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    public function correctVariationItems($configurableProductId, array $attributes)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    public function getParentOfVariation($variationId, array $with = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getAttributeIdsOfChildrenProduct($productId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
