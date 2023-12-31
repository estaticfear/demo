<?php

namespace Cmat\AuditLog\Providers;

use Cmat\AuditLog\Facades\AuditLogFacade;
use Cmat\AuditLog\Models\AuditHistory;
use Cmat\AuditLog\Repositories\Caches\AuditLogCacheDecorator;
use Cmat\AuditLog\Repositories\Eloquent\AuditLogRepository;
use Cmat\AuditLog\Repositories\Interfaces\AuditLogInterface;
use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

/**
 * @since 02/07/2016 09:05 AM
 */
class AuditLogServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(AuditLogInterface::class, function () {
            return new AuditLogCacheDecorator(new AuditLogRepository(new AuditHistory()));
        });

        AliasLoader::getInstance()->alias('AuditLog', AuditLogFacade::class);
    }

    public function boot(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(CommandServiceProvider::class);

        $this->setNamespace('plugins/audit-log')
            ->loadHelpers()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->publishAssets();

        $this->app['events']->listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-plugin-audit-log',
                    'priority' => 8,
                    'parent_id' => 'cms-core-platform-administration',
                    'name' => 'plugins/audit-log::history.name',
                    'icon' => null,
                    'url' => route('audit-log.index'),
                    'permissions' => ['audit-log.index'],
                ]);
        });

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);

            $schedule = $this->app->make(Schedule::class);

            $schedule->command('model:prune', ['--model' => AuditHistory::class])->dailyAt('00:30');
        });
    }
}
