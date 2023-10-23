<?php

namespace Cmat\Base\Providers;

use Cmat\Base\Commands\ActivateLicenseCommand;
use Cmat\Base\Commands\CleanupSystemCommand;
use Cmat\Base\Commands\ClearLogCommand;
use Cmat\Base\Commands\ExportDatabaseCommand;
use Cmat\Base\Commands\FetchGoogleFontsCommand;
use Cmat\Base\Commands\InstallCommand;
use Cmat\Base\Commands\PublishAssetsCommand;
use Cmat\Base\Commands\UpdateCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            ClearLogCommand::class,
            InstallCommand::class,
            UpdateCommand::class,
            PublishAssetsCommand::class,
            CleanupSystemCommand::class,
            ExportDatabaseCommand::class,
            FetchGoogleFontsCommand::class,
            ActivateLicenseCommand::class,
        ]);
    }
}
