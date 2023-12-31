<?php

namespace Cmat\Block\Providers;

use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Cmat\Block\Models\Block;
use Cmat\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use Cmat\Block\Repositories\Caches\BlockCacheDecorator;
use Cmat\Block\Repositories\Eloquent\BlockRepository;
use Cmat\Block\Repositories\Interfaces\BlockInterface;
use Language;

class BlockServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(BlockInterface::class, function () {
            return new BlockCacheDecorator(new BlockRepository(new Block()));
        });
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/block')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadMigrations();

        $this->app['events']->listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-plugins-block',
                'priority' => 6,
                'parent_id' => null,
                'name' => 'plugins/block::block.menu',
                'icon' => 'fa fa-code',
                'url' => route('block.index'),
                'permissions' => ['block.index'],
            ]);
        });

        $useLanguageV2 = $this->app['config']->get('plugins.block.general.use_language_v2', false) &&
            defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME');

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            if ($useLanguageV2) {
                LanguageAdvancedManager::registerModule(Block::class, [
                    'name',
                    'description',
                    'content',
                ]);
            } else {
                $this->app->booted(function () {
                    Language::registerModule([Block::class]);
                });
            }
        }

        $this->app->booted(function () use ($useLanguageV2) {
            if (defined('CUSTOM_FIELD_MODULE_SCREEN_NAME')) {
                \CustomField::registerModule(Block::class)
                    ->registerRule('basic', trans('plugins/block::block.name'), Block::class, function () {
                        return $this->app->make(BlockInterface::class)
                            ->getModel()
                            ->select([
                                'id',
                                'name',
                            ])
                            ->orderBy('created_at', 'DESC')
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->expandRule('other', trans('plugins/custom-field::rules.model_name'), 'model_name', function () {
                        return [
                            Block::class => trans('plugins/block::block.name'),
                        ];
                    });
            }

            $this->app->register(HookServiceProvider::class);
        });
    }
}
