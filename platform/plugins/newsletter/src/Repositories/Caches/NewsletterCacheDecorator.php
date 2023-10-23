<?php

namespace Cmat\Newsletter\Repositories\Caches;

use Cmat\Newsletter\Repositories\Interfaces\NewsletterInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class NewsletterCacheDecorator extends CacheAbstractDecorator implements NewsletterInterface
{
}
