<?php

namespace Cmat\ACL\Providers;

use Cmat\ACL\Http\Middleware\Authenticate;
use Cmat\ACL\Http\Middleware\RedirectIfAuthenticated;
use Cmat\ACL\Models\Activation;
use Cmat\ACL\Models\Role;
use Cmat\ACL\Models\User;
use Cmat\ACL\Repositories\Caches\RoleCacheDecorator;
use Cmat\ACL\Repositories\Eloquent\ActivationRepository;
use Cmat\ACL\Repositories\Eloquent\RoleRepository;
use Cmat\ACL\Repositories\Eloquent\UserRepository;
use Cmat\ACL\Repositories\Interfaces\ActivationInterface;
use Cmat\ACL\Repositories\Interfaces\RoleInterface;
use Cmat\ACL\Repositories\Interfaces\UserInterface;
use Cmat\Base\Traits\LoadAndPublishDataTrait;
use EmailHandler;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class AclServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(UserInterface::class, function () {
            return new UserRepository(new User());
        });

        $this->app->bind(ActivationInterface::class, function () {
            return new ActivationRepository(new Activation());
        });

        $this->app->bind(RoleInterface::class, function () {
            return new RoleCacheDecorator(new RoleRepository(new Role()));
        });
    }

    /**
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $this->app->register(CommandServiceProvider::class);
        $this->app->register(EventServiceProvider::class);

        $this->setNamespace('core/acl')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['general', 'permissions', 'email'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets()
            ->loadRoutes()
            ->loadMigrations();

        $this->garbageCollect();

        $this->app['events']->listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-core-role-permission',
                    'priority' => 2,
                    'parent_id' => 'cms-core-platform-administration',
                    'name' => 'core/acl::permissions.role_permission',
                    'icon' => null,
                    'url' => route('roles.index'),
                    'permissions' => ['roles.index'],
                ])
                ->registerItem([
                    'id' => 'cms-core-user',
                    'priority' => 3,
                    'parent_id' => 'cms-core-platform-administration',
                    'name' => 'core/acl::users.users',
                    'icon' => null,
                    'url' => route('users.index'),
                    'permissions' => ['users.index'],
                ]);

            /**
             * @var Router $router
             */
            $router = $this->app['router'];

            $router->aliasMiddleware('auth', Authenticate::class);
            $router->aliasMiddleware('guest', RedirectIfAuthenticated::class);
        });

        $this->app->booted(function () {
            config()->set(['auth.providers.users.model' => User::class]);

            EmailHandler::addTemplateSettings('acl', config('core.acl.email', []), 'core');

            $this->app->register(HookServiceProvider::class);
        });
    }

    /**
     * Garbage collect activations and reminders.
     *
     * @throws BindingResolutionException
     */
    protected function garbageCollect(): void
    {
        $config = $this->app->make('config')->get('core.acl.general');

        $this->sweep($this->app->make(ActivationInterface::class), Arr::get($config, 'activations.lottery', [2, 100]));
    }

    protected function sweep(ActivationInterface $repository, array $lottery): void
    {
        if ($this->configHitsLottery($lottery)) {
            try {
                $repository->removeExpired();
            } catch (Exception $exception) {
                info($exception->getMessage());
            }
        }
    }

    /**
     * Determine if the configuration odds hit the lottery.
     */
    protected function configHitsLottery(array $lottery): bool
    {
        return mt_rand(1, $lottery[1]) <= $lottery[0];
    }
}
