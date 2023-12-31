<?php

namespace Cmat\RequestLog\Providers;

use Cmat\RequestLog\Commands\RequestLogClearCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RequestLogClearCommand::class,
            ]);
        }
    }
}
