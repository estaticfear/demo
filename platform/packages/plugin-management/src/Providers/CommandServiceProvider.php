<?php

namespace Cmat\PluginManagement\Providers;

use Cmat\PluginManagement\Commands\ClearCompiledCommand;
use Cmat\PluginManagement\Commands\IlluminateClearCompiledCommand as OverrideIlluminateClearCompiledCommand;
use Cmat\PluginManagement\Commands\PackageDiscoverCommand;
use Cmat\PluginManagement\Commands\PluginActivateAllCommand;
use Cmat\PluginManagement\Commands\PluginActivateCommand;
use Cmat\PluginManagement\Commands\PluginAssetsPublishCommand;
use Cmat\PluginManagement\Commands\PluginDeactivateAllCommand;
use Cmat\PluginManagement\Commands\PluginDeactivateCommand;
use Cmat\PluginManagement\Commands\PluginDiscoverCommand;
use Cmat\PluginManagement\Commands\PluginListCommand;
use Cmat\PluginManagement\Commands\PluginRemoveAllCommand;
use Cmat\PluginManagement\Commands\PluginRemoveCommand;
use Illuminate\Foundation\Console\ClearCompiledCommand as IlluminateClearCompiledCommand;
use Illuminate\Foundation\Console\PackageDiscoverCommand as IlluminatePackageDiscoverCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->extend(IlluminatePackageDiscoverCommand::class, function () {
            return $this->app->make(PackageDiscoverCommand::class);
        });

        $this->app->extend(IlluminateClearCompiledCommand::class, function () {
            return $this->app->make(OverrideIlluminateClearCompiledCommand::class);
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PluginAssetsPublishCommand::class,
                ClearCompiledCommand::class,
                PluginDiscoverCommand::class,
            ]);
        }

        $this->commands([
            PluginActivateCommand::class,
            PluginActivateAllCommand::class,
            PluginDeactivateCommand::class,
            PluginDeactivateAllCommand::class,
            PluginRemoveCommand::class,
            PluginRemoveAllCommand::class,
            PluginListCommand::class,
        ]);
    }
}
