<?php

namespace Cmat\PluginGenerator\Providers;

use Cmat\PluginGenerator\Commands\PluginCreateCommand;
use Cmat\PluginGenerator\Commands\PluginMakeCrudCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PluginCreateCommand::class,
                PluginMakeCrudCommand::class,
            ]);
        }
    }
}
