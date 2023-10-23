<?php

namespace Cmat\AuditLog\Providers;

use Cmat\AuditLog\Events\AuditHandlerEvent;
use Cmat\AuditLog\Listeners\AuditHandlerListener;
use Cmat\AuditLog\Listeners\CreatedContentListener;
use Cmat\AuditLog\Listeners\DeletedContentListener;
use Cmat\AuditLog\Listeners\LoginListener;
use Cmat\AuditLog\Listeners\UpdatedContentListener;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AuditHandlerEvent::class => [
            AuditHandlerListener::class,
        ],
        Login::class => [
            LoginListener::class,
        ],
        UpdatedContentEvent::class => [
            UpdatedContentListener::class,
        ],
        CreatedContentEvent::class => [
            CreatedContentListener::class,
        ],
        DeletedContentEvent::class => [
            DeletedContentListener::class,
        ],
    ];
}
