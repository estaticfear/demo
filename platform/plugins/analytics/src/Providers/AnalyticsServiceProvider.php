<?php

namespace Cmat\Analytics\Providers;

use Cmat\Analytics\Analytics;
use Cmat\Analytics\AnalyticsClient;
use Cmat\Analytics\AnalyticsClientFactory;
use Cmat\Analytics\Abstracts\AnalyticsAbstract;
use Cmat\Analytics\Facades\AnalyticsFacade;
use Cmat\Analytics\GA4\Analytics as AnalyticsGA4;
use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Cmat\Analytics\Exceptions\InvalidConfiguration;

class AnalyticsServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(AnalyticsClient::class, function () {
            return AnalyticsClientFactory::createForConfig(config('plugins.analytics.general'));
        });

        $this->app->bind(AnalyticsAbstract::class, function () {
            $credentials = setting('analytics_service_account_credentials');

            if (! $credentials) {
                throw InvalidConfiguration::credentialsIsNotValid();
            }

            if (setting('analytics_type', 'ua') == 'ga4' && $propertyId = setting('analytics_property_id')) {
                return new AnalyticsGA4($propertyId, $credentials);
            }

            $viewId = setting('analytics_view_id');

            if (empty($viewId)) {
                throw InvalidConfiguration::propertyIdNotSpecified();
            }

            return new Analytics($this->app->make(AnalyticsClient::class), $viewId);
        });

        AliasLoader::getInstance()->alias('Analytics', AnalyticsFacade::class);
    }

    public function boot(): void
    {
        $this->setNamespace('plugins/analytics')
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets();

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });
    }
}
