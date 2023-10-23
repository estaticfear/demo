<?php

namespace Cmat\Member\Repositories\Eloquent;

use Cmat\Member\Repositories\Interfaces\MemberActivityLogInterface;
use Cmat\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MemberActivityLogRepository extends RepositoriesAbstract implements MemberActivityLogInterface
{
    public function getAllLogs(int|string $memberId, int $paginate = 10): LengthAwarePaginator
    {
        return $this->model
            ->where('member_id', $memberId)
            ->latest('created_at')
            ->paginate($paginate);
    }
}
