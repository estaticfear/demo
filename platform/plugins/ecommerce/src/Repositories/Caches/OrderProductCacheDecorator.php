<?php

namespace Cmat\Ecommerce\Repositories\Caches;

use Cmat\Ecommerce\Repositories\Interfaces\OrderProductInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class OrderProductCacheDecorator extends CacheAbstractDecorator implements OrderProductInterface
{
}
