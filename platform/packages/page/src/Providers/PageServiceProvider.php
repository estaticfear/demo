<?php

namespace Cmat\Page\Providers;

use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Cmat\Page\Models\Page;
use Cmat\Page\Repositories\Caches\PageCacheDecorator;
use Cmat\Page\Repositories\Eloquent\PageRepository;
use Cmat\Page\Repositories\Interfaces\PageInterface;
use Cmat\Shortcode\View\View;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

/**
 * @since 02/07/2016 09:50 AM
 */
class PageServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->setNamespace('packages/page')
            ->loadHelpers();
    }

    public function boot(): void
    {
        $this->app->bind(PageInterface::class, function () {
            return new PageCacheDecorator(new PageRepository(new Page()));
        });

        $this
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadMigrations();

        $this->app['events']->listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-core-page',
                'priority' => 2,
                'parent_id' => null,
                'name' => 'packages/page::pages.menu_name',
                'icon' => 'fa fa-book',
                'url' => route('pages.index'),
                'permissions' => ['pages.index'],
            ]);

            if (function_exists('admin_bar')) {
                admin_bar()->registerLink(trans('packages/page::pages.menu_name'), route('pages.create'), 'add-new', 'pages.create');
            }
        });

        if (function_exists('shortcode')) {
            view()->composer(['packages/page::themes.page'], function (View $view) {
                $view->withShortcodes();
            });
        }

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });

        $this->app->register(EventServiceProvider::class);
    }
}
