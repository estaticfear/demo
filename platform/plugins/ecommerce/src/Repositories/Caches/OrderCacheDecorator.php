<?php

namespace Cmat\Ecommerce\Repositories\Caches;

use Cmat\Ecommerce\Repositories\Interfaces\OrderInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;

class OrderCacheDecorator extends CacheAbstractDecorator implements OrderInterface
{
    public function getRevenueData(CarbonInterface $startDate, CarbonInterface $endDate, array $select = []): Collection
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function countRevenueByDateRange(CarbonInterface $startDate, CarbonInterface $endDate): float
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
