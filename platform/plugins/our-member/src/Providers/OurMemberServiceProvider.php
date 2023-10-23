<?php

namespace Cmat\OurMember\Providers;

use Cmat\OurMember\Models\OurMember;
use Illuminate\Support\ServiceProvider;
use Cmat\OurMember\Repositories\Caches\OurMemberCacheDecorator;
use Cmat\OurMember\Repositories\Eloquent\OurMemberRepository;
use Cmat\OurMember\Repositories\Interfaces\OurMemberInterface;
use Illuminate\Support\Facades\Event;
use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class OurMemberServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(OurMemberInterface::class, function () {
            return new OurMemberCacheDecorator(new OurMemberRepository(new OurMember));
        });

        $this->setNamespace('plugins/our-member')->loadHelpers();
    }

    public function boot(): void
    {
        $this
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes();

        if (defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            \Cmat\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(OurMember::class, [
                'name',
            ]);
        }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-our-member',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/our-member::our-member.name',
                'icon'        => 'fa fa-list',
                'url'         => route('our-member.index'),
                'permissions' => ['our-member.index'],
            ]);
        });
    }
}
