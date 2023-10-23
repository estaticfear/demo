<?php

use Cmat\OurMember\Repositories\Interfaces\OurMemberInterface;
use Illuminate\Database\Eloquent\Collection;

if (! function_exists('get_our_active_members')) {
    function get_our_active_members(int $limit): Collection
    {
        return app(OurMemberInterface::class)->getOurActiveMembers($limit);
    }
}