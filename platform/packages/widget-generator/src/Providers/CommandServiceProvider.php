<?php

namespace Cmat\WidgetGenerator\Providers;

use Cmat\WidgetGenerator\Commands\WidgetCreateCommand;
use Cmat\WidgetGenerator\Commands\WidgetRemoveCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                WidgetCreateCommand::class,
                WidgetRemoveCommand::class,
            ]);
        }
    }
}
