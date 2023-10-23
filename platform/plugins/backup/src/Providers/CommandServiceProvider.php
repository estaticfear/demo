<?php

namespace Cmat\Backup\Providers;

use Cmat\Backup\Commands\BackupCleanCommand;
use Cmat\Backup\Commands\BackupCreateCommand;
use Cmat\Backup\Commands\BackupListCommand;
use Cmat\Backup\Commands\BackupRemoveCommand;
use Cmat\Backup\Commands\BackupRestoreCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                BackupCreateCommand::class,
                BackupRestoreCommand::class,
                BackupRemoveCommand::class,
                BackupListCommand::class,
                BackupCleanCommand::class,
            ]);
        }
    }
}
