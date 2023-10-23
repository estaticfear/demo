<?php

namespace Cmat\Ecommerce\Repositories\Caches;

use Cmat\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class OrderHistoryCacheDecorator extends CacheAbstractDecorator implements OrderHistoryInterface
{
}
