<?php

namespace Cmat\RequestLog\Providers;

use Cmat\RequestLog\Events\RequestHandlerEvent;
use Cmat\RequestLog\Listeners\RequestHandlerListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RequestHandlerEvent::class => [
            RequestHandlerListener::class,
        ],
    ];
}
