<?php

namespace Cmat\Contact\Providers;

use EmailHandler;
use Illuminate\Routing\Events\RouteMatched;
use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Cmat\Contact\Models\ContactReply;
use Cmat\Contact\Repositories\Caches\ContactReplyCacheDecorator;
use Cmat\Contact\Repositories\Eloquent\ContactReplyRepository;
use Cmat\Contact\Repositories\Interfaces\ContactInterface;
use Cmat\Contact\Models\Contact;
use Cmat\Contact\Repositories\Caches\ContactCacheDecorator;
use Cmat\Contact\Repositories\Eloquent\ContactRepository;
use Cmat\Contact\Repositories\Interfaces\ContactReplyInterface;
use Illuminate\Support\ServiceProvider;

class ContactServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(ContactInterface::class, function () {
            return new ContactCacheDecorator(new ContactRepository(new Contact()));
        });

        $this->app->bind(ContactReplyInterface::class, function () {
            return new ContactReplyCacheDecorator(new ContactReplyRepository(new ContactReply()));
        });
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/contact')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'email'])
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->publishAssets();

        $this->app['events']->listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-plugins-contact',
                'priority' => 120,
                'parent_id' => null,
                'name' => 'plugins/contact::contact.menu',
                'icon' => 'far fa-envelope',
                'url' => route('contacts.index'),
                'permissions' => ['contacts.index'],
            ]);

            EmailHandler::addTemplateSettings(CONTACT_MODULE_SCREEN_NAME, config('plugins.contact.email', []));
        });

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });
    }
}
