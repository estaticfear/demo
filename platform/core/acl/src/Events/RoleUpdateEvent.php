<?php

namespace Cmat\ACL\Events;

use Cmat\ACL\Models\Role;
use Cmat\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class RoleUpdateEvent extends Event
{
    use SerializesModels;

    public Role $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }
}
