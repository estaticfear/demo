<?php

namespace Cmat\OurMember\Repositories\Interfaces;

use Cmat\Support\Repositories\Interfaces\RepositoryInterface;

interface OurMemberInterface extends RepositoryInterface
{
    /**
     * @param int $perPage
     * @return mixed
     */
    public function getOurActiveMembers($perPage = 12);
}
