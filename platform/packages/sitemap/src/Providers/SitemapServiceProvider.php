<?php

namespace Cmat\Sitemap\Providers;

use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Cmat\Sitemap\Sitemap;
use Illuminate\Support\ServiceProvider;

class SitemapServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    protected bool $defer = true;

    public function boot(): void
    {
        $this->setNamespace('packages/sitemap')
            ->loadAndPublishConfigurations(['config'])
            ->loadAndPublishViews()
            ->publishAssets();

        $this->app['events']->listen(CreatedContentEvent::class, function () {
            cache()->forget('cache_site_map_key');
        });

        $this->app['events']->listen(UpdatedContentEvent::class, function () {
            cache()->forget('cache_site_map_key');
        });

        $this->app['events']->listen(DeletedContentEvent::class, function () {
            cache()->forget('cache_site_map_key');
        });
    }

    public function register(): void
    {
        $this->app->bind('sitemap', function ($app) {
            $config = config('packages.sitemap.config');

            return new Sitemap(
                $config,
                $app['Illuminate\Cache\Repository'],
                $app['config'],
                $app['files'],
                $app['Illuminate\Contracts\Routing\ResponseFactory'],
                $app['view']
            );
        });

        $this->app->alias('sitemap', Sitemap::class);
    }

    public function provides(): array
    {
        return ['sitemap', Sitemap::class];
    }
}
