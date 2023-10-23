<?php

namespace Cmat\Menu\Providers;

use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Menu\Listeners\DeleteMenuNodeListener;
use Cmat\Menu\Listeners\UpdateMenuNodeUrlListener;
use Cmat\Slug\Events\UpdatedSlugEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UpdatedSlugEvent::class => [
            UpdateMenuNodeUrlListener::class,
        ],
        DeletedContentEvent::class => [
            DeleteMenuNodeListener::class,
        ],
    ];
}
