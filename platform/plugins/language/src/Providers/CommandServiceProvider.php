<?php

namespace Cmat\Language\Providers;

use Cmat\Language\Commands\RouteCacheCommand;
use Cmat\Language\Commands\RouteClearCommand;
use Cmat\Language\Commands\SyncOldDataCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            SyncOldDataCommand::class,
        ]);

        $this->app->extend('command.route.cache', function () {
            return new RouteCacheCommand($this->app['files']);
        });

        $this->app->extend('command.route.clear', function () {
            return new RouteClearCommand($this->app['files']);
        });
    }
}
