<?php

namespace Cmat\OurMember\Repositories\Caches;

use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;
use Cmat\OurMember\Repositories\Interfaces\OurMemberInterface;

class OurMemberCacheDecorator extends CacheAbstractDecorator implements OurMemberInterface
{
    public function getOurActiveMembers($perPage = 12)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
