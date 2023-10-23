<?php

namespace Cmat\Ecommerce\Repositories\Caches;

use Cmat\Ecommerce\Repositories\Interfaces\OrderReturnItemInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class OrderReturnItemCacheDecorator extends CacheAbstractDecorator implements OrderReturnItemInterface
{
}
