<?php

namespace Cmat\Backup\Providers;

use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class BackupServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this->setNamespace('plugins/backup')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets();

        $this->app->register(CommandServiceProvider::class);

        $this->app['events']->listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-plugin-backup',
                'priority' => 8,
                'parent_id' => 'cms-core-platform-administration',
                'name' => 'plugins/backup::backup.menu_name',
                'icon' => null,
                'url' => route('backups.index'),
                'permissions' => ['backups.index'],
            ]);
        });

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });
    }
}
