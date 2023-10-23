<?php

namespace Cmat\AuditLog\Providers;

use Cmat\AuditLog\Commands\ActivityLogClearCommand;
use Cmat\AuditLog\Commands\CleanOldLogsCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ActivityLogClearCommand::class,
                CleanOldLogsCommand::class,
            ]);
        }
    }
}
