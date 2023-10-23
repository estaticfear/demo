<?php

namespace Cmat\PluginManagement\Providers;

use Cmat\Installer\Events\InstallerFinished;
use Cmat\PluginManagement\Listeners\ClearPluginCaches;
use Illuminate\Contracts\Database\Events\MigrationEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MigrationEvent::class => [
            ClearPluginCaches::class,
        ],
        InstallerFinished::class => [
            ClearPluginCaches::class,
        ],
    ];
}
