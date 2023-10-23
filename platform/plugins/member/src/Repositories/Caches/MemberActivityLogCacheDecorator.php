<?php

namespace Cmat\Member\Repositories\Caches;

use Cmat\Member\Repositories\Interfaces\MemberActivityLogInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MemberActivityLogCacheDecorator extends CacheAbstractDecorator implements MemberActivityLogInterface
{
    public function getAllLogs(int|string $memberId, int $paginate = 10): LengthAwarePaginator
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
