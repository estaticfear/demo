<?php

namespace Cmat\OurMember\Repositories\Eloquent;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Support\Repositories\Eloquent\RepositoriesAbstract;
use Cmat\OurMember\Repositories\Interfaces\OurMemberInterface;

class OurMemberRepository extends RepositoriesAbstract implements OurMemberInterface
{
    public function getOurActiveMembers($perPage = 12)
    {
        $data = $this->model
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data)->paginate($perPage);
    }
}
