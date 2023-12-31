<?php

namespace Cmat\LanguageAdvanced\Providers;

use Cmat\Base\Models\BaseModel;
use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Cmat\Language\Models\Language as LanguageModel;
use Cmat\LanguageAdvanced\Models\TranslationResolver;
use Cmat\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Cmat\Page\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Language;
use MacroableModels;

class LanguageAdvancedServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        if (is_plugin_active('language')) {
            $this->setNamespace('plugins/language-advanced')
                ->loadHelpers()
                ->loadMigrations()
                ->loadAndPublishConfigurations(['general'])
                ->loadAndPublishViews()
                ->loadRoutes();

            $this->app->register(HookServiceProvider::class);
            $this->app->register(EventServiceProvider::class);

            $this->app->booted(function () {
                foreach (LanguageAdvancedManager::getSupported() as $item => $columns) {
                    if (! class_exists($item)) {
                        continue;
                    }

                    /**
                     * @var BaseModel $item
                     */
                    $item::resolveRelationUsing('translations', function ($model) {
                        $instance = tap(
                            new TranslationResolver(),
                            function ($instance) {
                                if (! $instance->getConnectionName()) {
                                    $instance->setConnection(DB::getDefaultConnection());
                                }
                            }
                        );

                        $instance->setTable($model->getTable() . '_translations');

                        $instance->fillable(array_merge([
                            'lang_code',
                            $model->getTable() . '_id',
                        ], LanguageAdvancedManager::getTranslatableColumns(get_class($model))));

                        return new HasMany(
                            $instance->newQuery(),
                            $model,
                            $model->getTable() . '_translations.' . $model->getTable() . '_id',
                            $model->getKeyName()
                        );
                    });

                    foreach ($columns as $column) {
                        MacroableModels::addMacro(
                            $item,
                            'get' . ucfirst(Str::camel($column)) . 'Attribute',
                            function () use ($column, $item) {
                                /**
                                 * @var BaseModel $this
                                 */

                                $locale = is_in_admin() ? Language::getCurrentAdminLocaleCode() : Language::getCurrentLocaleCode();
                                if (! $this->lang_code && $locale != Language::getDefaultLocaleCode()) {
                                    $translation = $this->translations->where('lang_code', $locale)->first();

                                    if ($translation) {
                                        return $translation->{$column};
                                    }
                                }

                                return $this->getAttribute($column);
                            }
                        );
                    }
                }
            });

            $config = $this->app['config'];

            if ($config->get('plugins.language-advanced.general.page_use_language_v2', false)) {
                LanguageAdvancedManager::registerModule(Page::class, [
                    'name',
                    'description',
                    'content',
                ]);

                $supportedModels = Language::supportedModels();

                if (($key = array_search(Page::class, $supportedModels)) !== false) {
                    unset($supportedModels[$key]);
                }

                $config->set(['plugins.language.general.supported' => $supportedModels]);
            }

            $this->app['events']->listen('eloquent.deleted: ' . LanguageModel::class, function (LanguageModel $language) {
                foreach (LanguageAdvancedManager::getSupported() as $model => $columns) {
                    if (class_exists($model)) {
                        DB::table((new $model())->getTable() . '_translations')
                            ->where('lang_code', $language->lang_code)
                            ->delete();
                    }
                }
            });

            foreach (LanguageAdvancedManager::getSupported() as $model => $columns) {
                $this->app['events']->listen('eloquent.deleted: ' . $model, function (Model $model) {
                    DB::table($model->getTable() . '_translations')
                        ->where($model->getTable() . '_id', $model->getKey())
                        ->delete();
                });
            }
        }
    }
}
