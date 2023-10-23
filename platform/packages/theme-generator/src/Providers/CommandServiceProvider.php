<?php

namespace Cmat\ThemeGenerator\Providers;

use Cmat\ThemeGenerator\Commands\ThemeCreateCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ThemeCreateCommand::class,
            ]);
        }
    }
}
