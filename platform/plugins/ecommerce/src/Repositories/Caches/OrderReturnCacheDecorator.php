<?php

namespace Cmat\Ecommerce\Repositories\Caches;

use Cmat\Ecommerce\Repositories\Interfaces\OrderReturnInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class OrderReturnCacheDecorator extends CacheAbstractDecorator implements OrderReturnInterface
{
}
