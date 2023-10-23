<?php

namespace Cmat\DevTool\Providers;

use Cmat\DevTool\Commands\LocaleCreateCommand;
use Cmat\DevTool\Commands\LocaleRemoveCommand;
use Cmat\DevTool\Commands\Make\ControllerMakeCommand;
use Cmat\DevTool\Commands\Make\FormMakeCommand;
use Cmat\DevTool\Commands\Make\ModelMakeCommand;
use Cmat\DevTool\Commands\Make\RepositoryMakeCommand;
use Cmat\DevTool\Commands\Make\RequestMakeCommand;
use Cmat\DevTool\Commands\Make\RouteMakeCommand;
use Cmat\DevTool\Commands\Make\TableMakeCommand;
use Cmat\DevTool\Commands\PackageCreateCommand;
use Cmat\DevTool\Commands\PackageRemoveCommand;
use Cmat\DevTool\Commands\RebuildPermissionsCommand;
use Cmat\DevTool\Commands\TestSendMailCommand;
use Cmat\DevTool\Commands\PackageMakeCrudCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                TableMakeCommand::class,
                ControllerMakeCommand::class,
                RouteMakeCommand::class,
                RequestMakeCommand::class,
                FormMakeCommand::class,
                ModelMakeCommand::class,
                RepositoryMakeCommand::class,
                PackageCreateCommand::class,
                PackageMakeCrudCommand::class,
                PackageRemoveCommand::class,
                TestSendMailCommand::class,
                RebuildPermissionsCommand::class,
                LocaleRemoveCommand::class,
                LocaleCreateCommand::class,
            ]);
        }
    }
}
