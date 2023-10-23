<?php

namespace Cmat\Member\Providers;

use Cmat\Member\Listeners\UpdatedContentListener;
use Cmat\Base\Events\UpdatedContentEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UpdatedContentEvent::class => [
            UpdatedContentListener::class,
        ],
    ];
}
