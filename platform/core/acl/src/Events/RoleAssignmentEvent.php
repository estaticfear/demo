<?php

namespace Cmat\ACL\Events;

use Cmat\ACL\Models\Role;
use Cmat\ACL\Models\User;
use Cmat\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class RoleAssignmentEvent extends Event
{
    use SerializesModels;

    public Role $role;

    public User $user;

    public function __construct(Role $role, User $user)
    {
        $this->role = $role;
        $this->user = $user;
    }
}
