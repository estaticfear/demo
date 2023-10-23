<?php

namespace Cmat\Ecommerce\Repositories\Caches;

use Cmat\Ecommerce\Repositories\Interfaces\OrderAddressInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class OrderAddressCacheDecorator extends CacheAbstractDecorator implements OrderAddressInterface
{
}
