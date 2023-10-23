<?php

namespace Cmat\ACL\Repositories\Caches;

use Cmat\ACL\Repositories\Interfaces\RoleInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class RoleCacheDecorator extends CacheAbstractDecorator implements RoleInterface
{
    public function createSlug(string $name, int|string $id): string
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}
