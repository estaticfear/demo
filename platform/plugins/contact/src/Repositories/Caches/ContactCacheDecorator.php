<?php

namespace Cmat\Contact\Repositories\Caches;

use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;
use Cmat\Contact\Repositories\Interfaces\ContactInterface;
use Illuminate\Database\Eloquent\Collection;

class ContactCacheDecorator extends CacheAbstractDecorator implements ContactInterface
{
    public function getUnread(array $select = ['*']): Collection
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function countUnread(): int
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
