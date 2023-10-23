<?php

namespace Cmat\Dashboard\Providers;

use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Cmat\Dashboard\Models\DashboardWidget;
use Cmat\Dashboard\Models\DashboardWidgetSetting;
use Cmat\Dashboard\Repositories\Caches\DashboardWidgetCacheDecorator;
use Cmat\Dashboard\Repositories\Caches\DashboardWidgetSettingCacheDecorator;
use Cmat\Dashboard\Repositories\Eloquent\DashboardWidgetRepository;
use Cmat\Dashboard\Repositories\Eloquent\DashboardWidgetSettingRepository;
use Cmat\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Cmat\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

/**
 * @since 02/07/2016 09:50 AM
 */
class DashboardServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(DashboardWidgetInterface::class, function () {
            return new DashboardWidgetCacheDecorator(
                new DashboardWidgetRepository(new DashboardWidget())
            );
        });

        $this->app->bind(DashboardWidgetSettingInterface::class, function () {
            return new DashboardWidgetSettingCacheDecorator(
                new DashboardWidgetSettingRepository(new DashboardWidgetSetting())
            );
        });
    }

    public function boot(): void
    {
        $this->setNamespace('core/dashboard')
            ->loadHelpers()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets()
            ->loadMigrations();

        $this->app['events']->listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-core-dashboard',
                    'priority' => 0,
                    'parent_id' => null,
                    'name' => 'core/base::layouts.dashboard',
                    'icon' => 'fa fa-home',
                    'url' => route('dashboard.index'),
                    'permissions' => [],
                ]);
        });
    }
}
