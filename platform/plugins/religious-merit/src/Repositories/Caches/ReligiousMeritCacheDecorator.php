<?php

namespace Cmat\ReligiousMerit\Repositories\Caches;

use Cmat\ReligiousMerit\Enums\ReligiousMeritStatusEnum;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ReligiousMeritCacheDecorator extends CacheAbstractDecorator implements ReligiousMeritInterface
{
    /**
     * @param string $id
     * @param ReligiousMeritStatusEnum $status
     * @return mixed
     */
    public function updatePaymentStatus(string $id, ReligiousMeritStatusEnum $status)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    public function getMyMerits($limit, $keyword = '', array $orderBy = ['id' => 'DESC'], array $conditions = ['status' => ReligiousMeritStatusEnum::SUCCESS]): LengthAwarePaginator
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    public function getMyMeritsReport()
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}
