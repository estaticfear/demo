<?php

namespace Cmat\Member\Providers;

use ApiHelper;
use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Cmat\Blog\Models\Post;
use Cmat\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Cmat\Member\Http\Middleware\RedirectIfMember;
use Cmat\Member\Http\Middleware\RedirectIfNotMember;
use Cmat\Member\Models\Member;
use Cmat\Member\Models\MemberActivityLog;
use Cmat\Member\Repositories\Caches\MemberActivityLogCacheDecorator;
use Cmat\Member\Repositories\Caches\MemberCacheDecorator;
use Cmat\Member\Repositories\Eloquent\MemberActivityLogRepository;
use Cmat\Member\Repositories\Eloquent\MemberRepository;
use Cmat\Member\Repositories\Interfaces\MemberActivityLogInterface;
use Cmat\Member\Repositories\Interfaces\MemberInterface;
use EmailHandler;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Language;
use OptimizerHelper;
use SocialService;

class MemberServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        config([
            'auth.guards.member' => [
                'driver' => 'session',
                'provider' => 'members',
            ],
            'auth.providers.members' => [
                'driver' => 'eloquent',
                'model' => Member::class,
            ],
            'auth.passwords.members' => [
                'provider' => 'members',
                'table' => 'member_password_resets',
                'expire' => 60,
            ],
        ]);

        $router = $this->app['router'];

        $router->aliasMiddleware('member', RedirectIfNotMember::class);
        $router->aliasMiddleware('member.guest', RedirectIfMember::class);

        $this->app->bind(MemberInterface::class, function () {
            return new MemberCacheDecorator(new MemberRepository(new Member()));
        });

        $this->app->bind(MemberActivityLogInterface::class, function () {
            return new MemberActivityLogCacheDecorator(new MemberActivityLogRepository(new MemberActivityLog()));
        });
    }

    public function boot(): void
    {
        $this->setNamespace('plugins/member')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['general', 'permissions', 'assets', 'email'])
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes()
            ->loadMigrations()
            ->publishAssets();

        $this->app['events']->listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-core-member',
                'priority' => 22,
                'parent_id' => null,
                'name' => 'plugins/member::member.menu_name',
                'icon' => 'fa fa-users',
                'url' => route('member.index'),
                'permissions' => ['member.index'],
            ]);

            add_filter(IS_IN_ADMIN_FILTER, [$this, 'setInAdmin'], 24);
        });

        if (class_exists('ApiHelper') && ApiHelper::enabled()) {
            ApiHelper::setConfig([
                'model' => Member::class,
                'guard' => 'member',
                'password_broker' => 'members',
                'verify_email' => true,
            ]);
        }

        $this->app->booted(function () {
            EmailHandler::addTemplateSettings(MEMBER_MODULE_SCREEN_NAME, config('plugins.member.email', []));

            if (defined('SOCIAL_LOGIN_MODULE_SCREEN_NAME') && ! $this->app->runningInConsole() && Route::has('public.member.login')) {
                SocialService::registerModule([
                    'guard' => 'member',
                    'model' => Member::class,
                    'login_url' => route('public.member.login'),
                    'redirect_url' => route('public.member.dashboard'),
                ]);
            }
        });

        add_filter('social_login_before_saving_account', function ($data, $oAuth, $providerData) {
            if (Arr::get($providerData, 'model') == Member::class && Arr::get($providerData, 'guard') == 'member') {
                $firstName = implode(' ', explode(' ', $oAuth->getName(), -1));
                Arr::forget($data, 'name');
                $data = array_merge($data, [
                    'first_name' => $firstName,
                    'last_name' => trim(str_replace($firstName, '', $oAuth->getName())),
                ]);
            }

            return $data;
        }, 49, 3);

        $this->app->register(EventServiceProvider::class);

        add_action(BASE_ACTION_INIT, function (): void {
            if (defined('GALLERY_MODULE_SCREEN_NAME') && request()->segment(1) == 'account') {
                \Gallery::removeModule(Post::class);
            }
        }, 12, 2);

        add_filter(BASE_FILTER_AFTER_SETTING_CONTENT, [$this, 'addSettings'], 49);

        if (is_plugin_active('language') && is_plugin_active('language-advanced')) {
            add_filter(BASE_FILTER_BEFORE_RENDER_FORM, function ($form, $data) {
                if (in_array('member', Route::current()->middleware()) &&
                    Auth::guard('member')->check() &&
                    Language::getCurrentAdminLocaleCode() != Language::getDefaultLocaleCode() &&
                    $data &&
                    $data->id &&
                    LanguageAdvancedManager::isSupported($data)
                ) {
                    $refLang = null;

                    if (Language::getCurrentAdminLocaleCode() != Language::getDefaultLocaleCode()) {
                        $refLang = '?ref_lang=' . Language::getCurrentAdminLocaleCode();
                    }

                    $form->setFormOption(
                        'url',
                        route('public.member.language-advanced.save', $data->id) . $refLang
                    );
                }

                return $form;
            }, 9999, 2);
        }
    }

    public function setInAdmin(bool $isInAdmin): bool
    {
        $isInAdmin = in_array('member', Route::current()->middleware()) || $isInAdmin;

        if ($isInAdmin) {
            OptimizerHelper::disable();
        }

        return $isInAdmin;
    }

    public function addSettings(?string $data = null): string
    {
        return $data . view('plugins/member::settings')->render();
    }
}
