<?php

namespace Cmat\SeoHelper\Providers;

use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\SeoHelper\Listeners\CreatedContentListener;
use Cmat\SeoHelper\Listeners\DeletedContentListener;
use Cmat\SeoHelper\Listeners\UpdatedContentListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
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
