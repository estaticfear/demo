<?php

namespace Cmat\SeoHelper\Providers;

use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Cmat\SeoHelper\Contracts\SeoHelperContract;
use Cmat\SeoHelper\Contracts\SeoMetaContract;
use Cmat\SeoHelper\Contracts\SeoOpenGraphContract;
use Cmat\SeoHelper\Contracts\SeoTwitterContract;
use Cmat\SeoHelper\SeoHelper;
use Cmat\SeoHelper\SeoMeta;
use Cmat\SeoHelper\SeoOpenGraph;
use Cmat\SeoHelper\SeoTwitter;
use Illuminate\Support\ServiceProvider;

/**
 * @since 02/12/2015 14:09 PM
 */
class SeoHelperServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(SeoMetaContract::class, SeoMeta::class);
        $this->app->bind(SeoHelperContract::class, SeoHelper::class);
        $this->app->bind(SeoOpenGraphContract::class, SeoOpenGraph::class);
        $this->app->bind(SeoTwitterContract::class, SeoTwitter::class);

        $this->setNamespace('packages/seo-helper')
            ->loadHelpers();
    }

    public function boot(): void
    {
        $this
            ->loadAndPublishConfigurations(['general'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets();

        $this->app->register(EventServiceProvider::class);
        $this->app->register(HookServiceProvider::class);
    }
}
