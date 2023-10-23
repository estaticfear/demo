<?php

namespace Cmat\Analytics\GA4\Traits;

use Cmat\Analytics\Period;

trait CustomRetentionTrait
{
    public function getTotalNewAndReturningUsers(Period $period): array
    {
        return $this->dateRange($period)
            ->metrics('totalUsers')
            ->dimensions('newVsReturning')
            ->get()
            ->table;
    }

    public function getTotalNewAndReturningUsersByDate(Period $period): array
    {
        return $this->dateRange($period)
            ->metrics('totalUsers')
            ->dimensions('newVsReturning', 'date')
            ->orderByDimension('date')
            ->keepEmptyRows(true)
            ->get()
            ->table;
    }
}
