<?php

namespace Cmat\Blog\Providers;

use Cmat\Theme\Events\RenderingSiteMapEvent;
use Cmat\Blog\Listeners\RenderingSiteMapListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RenderingSiteMapEvent::class => [
            RenderingSiteMapListener::class,
        ],
    ];
}
