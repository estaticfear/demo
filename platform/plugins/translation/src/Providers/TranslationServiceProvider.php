<?php

namespace Cmat\Translation\Providers;

use Cmat\Translation\Console\DownloadLocaleCommand;
use Cmat\Translation\Console\RemoveUnusedTranslationsCommand;
use Cmat\Translation\Console\UpdateThemeTranslationCommand;
use Illuminate\Routing\Events\RouteMatched;
use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Cmat\Translation\Console\CleanCommand;
use Cmat\Translation\Console\ExportCommand;
use Cmat\Translation\Console\ImportCommand;
use Cmat\Translation\Console\ResetCommand;
use Cmat\Translation\Manager;
use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind('translation-manager', Manager::class);

        $this->commands([
            ImportCommand::class,
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ResetCommand::class,
                ExportCommand::class,
                CleanCommand::class,
                UpdateThemeTranslationCommand::class,
                RemoveUnusedTranslationsCommand::class,
                DownloadLocaleCommand::class,
            ]);
        }
    }

    public function boot(): void
    {
        $this->setNamespace('plugins/translation')
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadMigrations()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets();

        $this->app['events']->listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-plugin-translation',
                    'priority' => 997,
                    'parent_id' => null,
                    'name' => 'plugins/translation::translation.translations',
                    'icon' => 'fas fa-language',
                    'url' => route('translations.index'),
                    'permissions' => ['translations.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugin-translation-locale',
                    'priority' => 1,
                    'parent_id' => 'cms-plugin-translation',
                    'name' => 'plugins/translation::translation.locales',
                    'icon' => null,
                    'url' => route('translations.locales'),
                    'permissions' => ['translations.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugin-translation-theme-translations',
                    'priority' => 2,
                    'parent_id' => 'cms-plugin-translation',
                    'name' => 'plugins/translation::translation.theme-translations',
                    'icon' => null,
                    'url' => route('translations.theme-translations'),
                    'permissions' => ['translations.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugin-translation-admin-translations',
                    'priority' => 3,
                    'parent_id' => 'cms-plugin-translation',
                    'name' => 'plugins/translation::translation.admin-translations',
                    'icon' => null,
                    'url' => route('translations.index'),
                    'permissions' => ['translations.index'],
                ]);
        });
    }
}
