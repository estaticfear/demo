<?php

namespace Cmat\Blog\Providers;

use ApiHelper;
use Cmat\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Cmat\Shortcode\View\View;
use Illuminate\Routing\Events\RouteMatched;
use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Cmat\Blog\Models\Post;
use Cmat\Blog\Repositories\Caches\PostCacheDecorator;
use Cmat\Blog\Repositories\Eloquent\PostRepository;
use Cmat\Blog\Repositories\Interfaces\PostInterface;
use Illuminate\Support\ServiceProvider;
use Cmat\Blog\Models\Category;
use Cmat\Blog\Repositories\Caches\CategoryCacheDecorator;
use Cmat\Blog\Repositories\Eloquent\CategoryRepository;
use Cmat\Blog\Repositories\Interfaces\CategoryInterface;
use Cmat\Blog\Models\Tag;
use Cmat\Blog\Repositories\Caches\TagCacheDecorator;
use Cmat\Blog\Repositories\Eloquent\TagRepository;
use Cmat\Blog\Repositories\Interfaces\TagInterface;
use Language;
use Note;
use SeoHelper;
use SlugHelper;

/**
 * @since 02/07/2016 09:50 AM
 */
class BlogServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(PostInterface::class, function () {
            return new PostCacheDecorator(new PostRepository(new Post()));
        });

        $this->app->bind(CategoryInterface::class, function () {
            return new CategoryCacheDecorator(new CategoryRepository(new Category()));
        });

        $this->app->bind(TagInterface::class, function () {
            return new TagCacheDecorator(new TagRepository(new Tag()));
        });
    }

    public function boot(): void
    {
        SlugHelper::registerModule(Post::class, 'Blog Posts');
        SlugHelper::registerModule(Category::class, 'Blog Categories');
        SlugHelper::registerModule(Tag::class, 'Blog Tags');

        SlugHelper::setPrefix(Tag::class, 'tag', true);
        SlugHelper::setPrefix(Post::class, null, true);
        SlugHelper::setPrefix(Category::class, null, true);

        $this->setNamespace('plugins/blog')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadMigrations()
            ->publishAssets();

        if (ApiHelper::enabled()) {
            $this->loadRoutes(['api']);
        }

        $this->app->register(EventServiceProvider::class);

        $this->app['events']->listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-plugins-blog',
                    'priority' => 3,
                    'parent_id' => null,
                    'name' => 'plugins/blog::base.menu_name',
                    'icon' => 'fa fa-edit',
                    'url' => route('posts.index'),
                    'permissions' => ['posts.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-blog-post',
                    'priority' => 1,
                    'parent_id' => 'cms-plugins-blog',
                    'name' => 'plugins/blog::posts.menu_name',
                    'icon' => null,
                    'url' => route('posts.index'),
                    'permissions' => ['posts.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-blog-categories',
                    'priority' => 2,
                    'parent_id' => 'cms-plugins-blog',
                    'name' => 'plugins/blog::categories.menu_name',
                    'icon' => null,
                    'url' => route('categories.index'),
                    'permissions' => ['categories.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-blog-tags',
                    'priority' => 3,
                    'parent_id' => 'cms-plugins-blog',
                    'name' => 'plugins/blog::tags.menu_name',
                    'icon' => null,
                    'url' => route('tags.index'),
                    'permissions' => ['tags.index'],
                ]);
        });

        $useLanguageV2 = $this->app['config']->get('plugins.blog.general.use_language_v2', false) &&
            defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME');

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && $useLanguageV2) {
            LanguageAdvancedManager::registerModule(Post::class, [
                'name',
                'description',
                'content',
            ]);

            LanguageAdvancedManager::registerModule(Category::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(Tag::class, [
                'name',
                'description',
            ]);
        }

        $this->app->booted(function () use ($useLanguageV2) {
            $models = [Post::class, Category::class, Tag::class];

            if (defined('LANGUAGE_MODULE_SCREEN_NAME') && ! $useLanguageV2) {
                Language::registerModule($models);
            }

            SeoHelper::registerModule($models);

            $configKey = 'packages.revision.general.supported';
            config()->set($configKey, array_merge(config($configKey, []), [Post::class]));

            if (defined('NOTE_FILTER_MODEL_USING_NOTE')) {
                Note::registerModule(Post::class);
            }

            $this->app->register(HookServiceProvider::class);
        });

        if (function_exists('shortcode')) {
            view()->composer([
                'plugins/blog::themes.post',
                'plugins/blog::themes.category',
                'plugins/blog::themes.tag',
            ], function (View $view) {
                $view->withShortcodes();
            });
        }
    }
}
