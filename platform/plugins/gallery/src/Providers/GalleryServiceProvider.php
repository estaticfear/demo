<?php

namespace Cmat\Gallery\Providers;

use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Cmat\Gallery\Facades\GalleryFacade;
use Cmat\Gallery\Models\Gallery;
use Cmat\Gallery\Models\GalleryMeta;
use Cmat\Gallery\Repositories\Caches\GalleryCacheDecorator;
use Cmat\Gallery\Repositories\Caches\GalleryMetaCacheDecorator;
use Cmat\Gallery\Repositories\Eloquent\GalleryMetaRepository;
use Cmat\Gallery\Repositories\Eloquent\GalleryRepository;
use Cmat\Gallery\Repositories\Interfaces\GalleryInterface;
use Cmat\Gallery\Repositories\Interfaces\GalleryMetaInterface;
use Cmat\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use Language;
use SeoHelper;
use SlugHelper;

class GalleryServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(GalleryInterface::class, function () {
            return new GalleryCacheDecorator(
                new GalleryRepository(new Gallery())
            );
        });

        $this->app->bind(GalleryMetaInterface::class, function () {
            return new GalleryMetaCacheDecorator(
                new GalleryMetaRepository(new GalleryMeta())
            );
        });

        AliasLoader::getInstance()->alias('Gallery', GalleryFacade::class);
    }

    public function boot(): void
    {
        SlugHelper::registerModule(Gallery::class, 'Galleries');
        SlugHelper::setPrefix(Gallery::class, 'galleries', true);

        $this
            ->setNamespace('plugins/gallery')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->publishAssets();

        $this->app->register(EventServiceProvider::class);

        $this->app['events']->listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-plugins-gallery',
                'priority' => 5,
                'parent_id' => null,
                'name' => 'plugins/gallery::gallery.menu_name',
                'icon' => 'fa fa-camera',
                'url' => route('galleries.index'),
                'permissions' => ['galleries.index'],
            ]);
        });

        $useLanguageV2 = $this->app['config']->get('plugins.gallery.general.use_language_v2', false) &&
            defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME');

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            if ($useLanguageV2) {
                LanguageAdvancedManager::registerModule(Gallery::class, [
                    'name',
                    'description',
                ]);

                LanguageAdvancedManager::registerModule(GalleryMeta::class, [
                    'images',
                ]);

                LanguageAdvancedManager::addTranslatableMetaBox('gallery_wrap');

                foreach (\Gallery::getSupportedModules() as $item) {
                    $translatableColumns = array_merge(
                        LanguageAdvancedManager::getTranslatableColumns($item),
                        ['gallery']
                    );

                    LanguageAdvancedManager::registerModule($item, $translatableColumns);
                }
            } else {
                $this->app->booted(function () {
                    Language::registerModule([Gallery::class]);
                });
            }
        }

        $this->app->booted(function () {
            SeoHelper::registerModule([Gallery::class]);

            $this->app->register(HookServiceProvider::class);
        });
    }
}
