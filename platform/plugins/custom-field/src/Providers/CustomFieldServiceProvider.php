<?php

namespace Cmat\CustomField\Providers;

use Cmat\ACL\Repositories\Interfaces\RoleInterface;
use Cmat\ACL\Repositories\Interfaces\UserInterface;
use Cmat\Blog\Models\Category;
use Cmat\Blog\Models\Post;
use Cmat\CustomField\Support\CustomFieldSupport;
use Cmat\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Cmat\Page\Models\Page;
use Illuminate\Routing\Events\RouteMatched;
use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Cmat\CustomField\Facades\CustomFieldSupportFacade;
use Cmat\Page\Repositories\Interfaces\PageInterface;
use Cmat\CustomField\Models\CustomField;
use Cmat\CustomField\Models\FieldGroup;
use Cmat\CustomField\Models\FieldItem;
use Cmat\CustomField\Repositories\Caches\CustomFieldCacheDecorator;
use Cmat\CustomField\Repositories\Eloquent\CustomFieldRepository;
use Cmat\CustomField\Repositories\Caches\FieldGroupCacheDecorator;
use Cmat\CustomField\Repositories\Eloquent\FieldGroupRepository;
use Cmat\CustomField\Repositories\Caches\FieldItemCacheDecorator;
use Cmat\CustomField\Repositories\Eloquent\FieldItemRepository;
use Cmat\CustomField\Repositories\Interfaces\CustomFieldInterface;
use Cmat\CustomField\Repositories\Interfaces\FieldGroupInterface;
use Cmat\CustomField\Repositories\Interfaces\FieldItemInterface;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class CustomFieldServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('CustomField', CustomFieldSupportFacade::class);

        $this->app->bind(CustomFieldInterface::class, function () {
            return new CustomFieldCacheDecorator(
                new CustomFieldRepository(new CustomField()),
                CUSTOM_FIELD_CACHE_GROUP
            );
        });

        $this->app->bind(FieldGroupInterface::class, function () {
            return new FieldGroupCacheDecorator(
                new FieldGroupRepository(new FieldGroup()),
                CUSTOM_FIELD_CACHE_GROUP
            );
        });

        $this->app->bind(FieldItemInterface::class, function () {
            return new FieldItemCacheDecorator(
                new FieldItemRepository(new FieldItem()),
                CUSTOM_FIELD_CACHE_GROUP
            );
        });
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/custom-field')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadMigrations()
            ->publishAssets();

        $this->app->register(EventServiceProvider::class);

        $this->app['events']->listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-plugins-custom-field',
                'priority' => 5,
                'parent_id' => null,
                'name' => 'plugins/custom-field::base.admin_menu.title',
                'icon' => 'fas fa-cubes',
                'url' => route('custom-fields.index'),
                'permissions' => ['custom-fields.index'],
            ]);

            $this->registerUsersFields();
            $this->registerPagesFields();

            if (is_plugin_active('blog')) {
                $this->registerBlogFields();
            }
        });

        if (defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            LanguageAdvancedManager::registerModule(CustomField::class, [
                'value',
            ]);
        }

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });
    }

    protected function registerUsersFields(): CustomFieldSupport
    {
        return CustomFieldSupportFacade::registerRule(
            'other',
            trans('plugins/custom-field::rules.logged_in_user'),
            'logged_in_user',
            function () {
                $users = $this->app->make(UserInterface::class)->all();
                $userArr = [];
                foreach ($users as $user) {
                    $userArr[$user->id] = $user->username . ' - ' . $user->email;
                }

                return $userArr;
            }
        )
            ->registerRule(
                'other',
                trans('plugins/custom-field::rules.logged_in_user_has_role'),
                'logged_in_user_has_role',
                function () {
                    $roles = $this->app->make(RoleInterface::class)->all();
                    $rolesArr = [];
                    foreach ($roles as $role) {
                        $rolesArr[$role->slug] = $role->name . ' - (' . $role->slug . ')';
                    }

                    return $rolesArr;
                }
            );
    }

    protected function registerPagesFields(): bool|CustomFieldSupport
    {
        if (! defined('PAGE_MODULE_SCREEN_NAME')) {
            return false;
        }

        return CustomFieldSupportFacade::registerRule(
            'basic',
            trans('plugins/custom-field::rules.page_template'),
            'page_template',
            function () {
                return get_page_templates();
            }
        )
            ->registerRule('basic', trans('plugins/custom-field::rules.page'), Page::class, function () {
                return $this->app->make(PageInterface::class)
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
                    Page::class => trans('plugins/custom-field::rules.model_name_page'),
                ];
            });
    }

    protected function registerBlogFields(): bool|CustomFieldSupport
    {
        if (! defined('POST_MODULE_SCREEN_NAME')) {
            return false;
        }

        return CustomFieldSupportFacade::registerRuleGroup('blog')
            ->registerRule('blog', trans('plugins/custom-field::rules.category'), Category::class, function () {
                return $this->getBlogCategoryIds();
            })
            ->registerRule(
                'blog',
                trans('plugins/custom-field::rules.post_with_related_category'),
                Post::class . '_post_with_related_category',
                function () {
                    return $this->getBlogCategoryIds();
                }
            )
            ->registerRule(
                'blog',
                trans('plugins/custom-field::rules.post_format'),
                Post::class . '_post_format',
                function () {
                    $formats = [];
                    foreach (get_post_formats() as $key => $format) {
                        $formats[$key] = $format['name'];
                    }

                    return $formats;
                }
            )
            ->expandRule('other', trans('plugins/custom-field::rules.model_name'), 'model_name', function () {
                return [
                    Post::class => trans('plugins/custom-field::rules.model_name_post'),
                    Category::class => trans('plugins/custom-field::rules.model_name_category'),
                ];
            });
    }

    protected function getBlogCategoryIds(): array
    {
        $categories = get_categories();

        $categoriesArr = [];
        foreach ($categories as $row) {
            $categoriesArr[$row->id] = $row->indent_text . ' ' . $row->name;
        }

        return $categoriesArr;
    }
}
