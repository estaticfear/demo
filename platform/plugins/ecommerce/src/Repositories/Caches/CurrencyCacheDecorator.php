<?php

namespace Cmat\Ecommerce\Repositories\Caches;

use Cmat\Ecommerce\Repositories\Interfaces\CurrencyInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class CurrencyCacheDecorator extends CacheAbstractDecorator implements CurrencyInterface
{
    public function getAllCurrencies()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
