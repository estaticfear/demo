<?php

namespace Cmat\Vnpay\Repositories\Caches;

use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;
use Cmat\Vnpay\Repositories\Interfaces\VnpayInterface;

class VnpayCacheDecorator extends CacheAbstractDecorator implements VnpayInterface
{
    public function creatVnpayTransaction($data, $return_url)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}
