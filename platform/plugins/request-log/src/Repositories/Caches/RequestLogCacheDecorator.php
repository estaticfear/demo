<?php

namespace Cmat\RequestLog\Repositories\Caches;

use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;
use Cmat\RequestLog\Repositories\Interfaces\RequestLogInterface;

class RequestLogCacheDecorator extends CacheAbstractDecorator implements RequestLogInterface
{
}
