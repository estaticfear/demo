<?php

namespace Cmat\ACL\Providers;

use Cmat\ACL\Events\RoleAssignmentEvent;
use Cmat\ACL\Events\RoleUpdateEvent;
use Cmat\ACL\Listeners\LoginListener;
use Cmat\ACL\Listeners\RoleAssignmentListener;
use Cmat\ACL\Listeners\RoleUpdateListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RoleUpdateEvent::class => [
            RoleUpdateListener::class,
        ],
        RoleAssignmentEvent::class => [
            RoleAssignmentListener::class,
        ],
        Login::class => [
            LoginListener::class,
        ],
    ];
}
