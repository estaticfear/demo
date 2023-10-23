<?php

namespace Cmat\Vnpay\Providers;

use Cmat\Vnpay\Models\VnpayTransaction;
use Illuminate\Support\ServiceProvider;
use Cmat\Vnpay\Repositories\Caches\VnpayCacheDecorator;
use Cmat\Vnpay\Repositories\Eloquent\VnpayRepository;
use Cmat\Vnpay\Repositories\Interfaces\VnpayInterface;
use Illuminate\Support\Facades\Event;
use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class VnpayServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(VnpayInterface::class, function () {
            return new VnpayCacheDecorator(new VnpayRepository(new VnpayTransaction));
        });

        $this->setNamespace('plugins/vnpay')->loadHelpers();
    }

    public function boot(): void
    {
        $this
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadHelpers()
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-vnpay',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/vnpay::vnpay.name',
                'icon'        => 'fa fa-list',
                'url'         => route('vnpay.index'),
                'permissions' => ['vnpay.index'],
            ]);

            dashboard_menu()->registerItem([
                'id' => 'cms-plugins-vnpay-setting',
                'priority' => 999,
                'parent_id' => 'cms-core-settings',
                'name' => 'plugins/vnpay::vnpay.name',
                'icon' => null,
                'url' => route('settings.vnpay'),
                'permissions' => ['vnpay.settings'],
            ]);
        });
    }
}
