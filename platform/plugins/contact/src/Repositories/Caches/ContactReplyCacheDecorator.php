<?php

namespace Cmat\Contact\Repositories\Caches;

use Cmat\Contact\Repositories\Interfaces\ContactReplyInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class ContactReplyCacheDecorator extends CacheAbstractDecorator implements ContactReplyInterface
{
}
