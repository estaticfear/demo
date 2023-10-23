<?php

namespace Cmat\Menu\Providers;

use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Cmat\Menu\Models\Menu as MenuModel;
use Cmat\Menu\Models\MenuLocation;
use Cmat\Menu\Models\MenuNode;
use Cmat\Menu\Repositories\Caches\MenuCacheDecorator;
use Cmat\Menu\Repositories\Caches\MenuLocationCacheDecorator;
use Cmat\Menu\Repositories\Caches\MenuNodeCacheDecorator;
use Cmat\Menu\Repositories\Eloquent\MenuLocationRepository;
use Cmat\Menu\Repositories\Eloquent\MenuNodeRepository;
use Cmat\Menu\Repositories\Eloquent\MenuRepository;
use Cmat\Menu\Repositories\Interfaces\MenuInterface;
use Cmat\Menu\Repositories\Interfaces\MenuLocationInterface;
use Cmat\Menu\Repositories\Interfaces\MenuNodeInterface;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->setNamespace('packages/menu')
            ->loadHelpers();
    }

    public function boot(): void
    {
        $this->app->bind(MenuInterface::class, function () {
            return new MenuCacheDecorator(
                new MenuRepository(new MenuModel())
            );
        });

        $this->app->bind(MenuNodeInterface::class, function () {
            return new MenuNodeCacheDecorator(
                new MenuNodeRepository(new MenuNode())
            );
        });

        $this->app->bind(MenuLocationInterface::class, function () {
            return new MenuLocationCacheDecorator(
                new MenuLocationRepository(new MenuLocation())
            );
        });

        $this
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->publishAssets();

        $this->app['events']->listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-core-menu',
                    'priority' => 2,
                    'parent_id' => 'cms-core-appearance',
                    'name' => 'packages/menu::menu.name',
                    'icon' => null,
                    'url' => route('menus.index'),
                    'permissions' => ['menus.index'],
                ]);

            if (! defined('THEME_MODULE_SCREEN_NAME')) {
                dashboard_menu()
                    ->registerItem([
                        'id' => 'cms-core-appearance',
                        'priority' => 996,
                        'parent_id' => null,
                        'name' => 'packages/theme::theme.appearance',
                        'icon' => 'fa fa-paint-brush',
                        'url' => '#',
                        'permissions' => [],
                    ]);
            }

            if (function_exists('admin_bar')) {
                admin_bar()->registerLink(trans('packages/menu::menu.name'), route('menus.index'), 'appearance', 'menus.index');
            }
        });

        $this->app->register(EventServiceProvider::class);
        $this->app->register(CommandServiceProvider::class);
    }
}
