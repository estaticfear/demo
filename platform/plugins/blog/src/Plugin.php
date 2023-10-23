<?php

namespace Cmat\Blog;

use Cmat\Blog\Models\Category;
use Cmat\Blog\Models\Tag;
use Cmat\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Cmat\Menu\Repositories\Interfaces\MenuNodeInterface;
use Cmat\Setting\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Cmat\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('post_tags');
        Schema::dropIfExists('post_categories');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('posts_translations');
        Schema::dropIfExists('categories_translations');
        Schema::dropIfExists('tags_translations');

        app(DashboardWidgetInterface::class)->deleteBy(['name' => 'widget_posts_recent']);

        app(MenuNodeInterface::class)->deleteBy(['reference_type' => Category::class]);
        app(MenuNodeInterface::class)->deleteBy(['reference_type' => Tag::class]);

        Setting::query()
            ->whereIn('key', [
                'blog_post_schema_enabled',
                'blog_post_schema_type',
            ])
            ->delete();
    }
}
