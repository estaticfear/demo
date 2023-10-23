<?php

namespace Cmat\Ecommerce\Repositories\Caches;

use Cmat\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class CustomerCacheDecorator extends CacheAbstractDecorator implements CustomerInterface
{
}
