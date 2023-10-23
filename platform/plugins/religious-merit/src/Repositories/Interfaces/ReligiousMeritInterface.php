<?php

namespace Cmat\ReligiousMerit\Repositories\Interfaces;

use Cmat\ReligiousMerit\Enums\ReligiousMeritStatusEnum;
use Cmat\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReligiousMeritInterface extends RepositoryInterface
{
    public function updatePaymentStatus(string $id, ReligiousMeritStatusEnum $status);

    public function getMyMerits($limit, $keyword = '', array $orderBy = ['id' => 'DESC'], array $conditions = ['status' => ReligiousMeritStatusEnum::SUCCESS]): LengthAwarePaginator;

    public function getMyMeritsReport();
}
