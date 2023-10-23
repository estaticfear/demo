<?php

namespace Cmat\Base\Providers;

use Cmat\Base\Events\AdminNotificationEvent;
use Cmat\Base\Events\BeforeEditContentEvent;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\SendMailEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Base\Listeners\AdminNotificationListener;
use Cmat\Base\Listeners\BeforeEditContentListener;
use Cmat\Base\Listeners\CreatedContentListener;
use Cmat\Base\Listeners\DeletedContentListener;
use Cmat\Base\Listeners\SendMailListener;
use Cmat\Base\Listeners\UpdatedContentListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SendMailEvent::class => [
            SendMailListener::class,
        ],
        CreatedContentEvent::class => [
            CreatedContentListener::class,
        ],
        UpdatedContentEvent::class => [
            UpdatedContentListener::class,
        ],
        DeletedContentEvent::class => [
            DeletedContentListener::class,
        ],
        BeforeEditContentEvent::class => [
            BeforeEditContentListener::class,
        ],
        AdminNotificationEvent::class => [
            AdminNotificationListener::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();

        $this->app['events']->listen(['cache:cleared'], function () {
            $this->app['files']->delete([storage_path('cache_keys.json'), storage_path('settings.json')]);
        });
    }
}
