<?php

namespace Cmat\Optimize\Providers;

use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Cmat\Optimize\Facades\OptimizerFacade;
use Cmat\Optimize\Http\Middleware\CollapseWhitespace;
use Cmat\Optimize\Http\Middleware\DeferJavascript;
use Cmat\Optimize\Http\Middleware\ElideAttributes;
use Cmat\Optimize\Http\Middleware\InlineCss;
use Cmat\Optimize\Http\Middleware\InsertDNSPrefetch;
use Cmat\Optimize\Http\Middleware\RemoveComments;
use Cmat\Optimize\Http\Middleware\RemoveQuotes;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use OptimizerHelper;

class OptimizeServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this->setNamespace('packages/optimize')
            ->loadAndPublishConfigurations(['general'])
            ->loadAndPublishTranslations()
            ->loadAndPublishViews();

        AliasLoader::getInstance()->alias('OptimizerHelper', OptimizerFacade::class);

        $this->app['events']->listen(RouteMatched::class, function () {
            if (OptimizerHelper::isEnabled()) {
                /**
                 * @var Router $router
                 */
                $router = $this->app['router'];

                if (setting('optimize_elide_attributes', 0)) {
                    $router->pushMiddlewareToGroup('web', ElideAttributes::class);
                }

                if (setting('optimize_inline_css', 0)) {
                    $router->pushMiddlewareToGroup('web', InlineCss::class);
                }

                if (setting('optimize_insert_dns_prefetch', 0)) {
                    $router->pushMiddlewareToGroup('web', InsertDNSPrefetch::class);
                }

                if (setting('optimize_collapse_white_space', 0)) {
                    $router->pushMiddlewareToGroup('web', CollapseWhitespace::class);
                }

                if (setting('optimize_remove_comments', 0)) {
                    $router->pushMiddlewareToGroup('web', RemoveComments::class);
                }

                if (setting('optimize_remove_quotes', 0)) {
                    $router->pushMiddlewareToGroup('web', RemoveQuotes::class);
                }

                if (setting('optimize_defer_javascript', 0)) {
                    $router->pushMiddlewareToGroup('web', DeferJavascript::class);
                }
            }
        });

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });
    }
}
