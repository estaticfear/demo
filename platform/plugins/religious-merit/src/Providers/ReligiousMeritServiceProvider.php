<?php

namespace Cmat\ReligiousMerit\Providers;

use Cmat\ReligiousMerit\Models\ReligiousMeritProjectCategory;
use Cmat\ReligiousMerit\Models\ReligiousMeritProject;
use Cmat\ReligiousMerit\Models\ReligiousMerit;

use Cmat\ReligiousMerit\Repositories\Caches\ReligiousMeritProjectCacheDecorator;
use Cmat\ReligiousMerit\Repositories\Eloquent\ReligiousMeritProjectRepository;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProjectInterface;

use Cmat\ReligiousMerit\Repositories\Caches\ReligiousMeritProjectCategoryCacheDecorator;
use Cmat\ReligiousMerit\Repositories\Eloquent\ReligiousMeritProjectCategoryRepository;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProjectCategoryInterface;

use Cmat\ReligiousMerit\Repositories\Caches\ReligiousMeritCacheDecorator;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritInterface;
use Cmat\ReligiousMerit\Repositories\Eloquent\ReligiousMeritRepository;

use Cmat\ReligiousMerit\Repositories\Caches\ReligiousMeritProductCacheDecorator;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProductInterface;
use Cmat\ReligiousMerit\Repositories\Eloquent\ReligiousMeritProductRepository;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Cmat\ReligiousMerit\Models\ReligiousMeritProduct;
use Illuminate\Routing\Events\RouteMatched;
use SlugHelper;
use Form;
use Illuminate\Database\Eloquent\Model;

class ReligiousMeritServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    protected function isSupported(string|Model $model): bool
    {
        if (is_object($model)) {
            $model = get_class($model);
        }
        return $model === 'Cmat\ReligiousMerit\Models\ReligiousMeritProject';
    }


    public function addCostEstimationTab(?string $tabs, string|Model|null $data = null): string
    {
        if (! empty($data) && $this->isSupported($data)) {
            return $tabs . view('plugins/religious-merit::forms.partials.project-cost-estimation-tab')->render();
        }
        return $tabs;
    }

    public function addCostEstimationContent(?string $tabs, string|Model|null $data = null): string
    {
        if (! empty($data) && $this->isSupported($data)) {
            return $tabs . view('plugins/religious-merit::forms.partials.project-cost-estimation-tab-content', ['model' => $data])->render();
        }
        return $tabs;
    }

    public function register(): void
    {
        // Danh mục dự án
        $this->app->bind(ReligiousMeritProjectCategoryInterface::class, function () {
            return new ReligiousMeritProjectCategoryCacheDecorator(new ReligiousMeritProjectCategoryRepository(new ReligiousMeritProjectCategory));
        });
        // Dự án
        $this->app->bind(ReligiousMeritProjectInterface::class, function () {
            return new ReligiousMeritProjectCacheDecorator(new ReligiousMeritProjectRepository(new ReligiousMeritProject));
        });
        // Công đức
        $this->app->bind(ReligiousMeritInterface::class, function () {
            return new ReligiousMeritCacheDecorator(new ReligiousMeritRepository(new ReligiousMerit));
        });
         // Sản phẩm trong công đức
         $this->app->bind(ReligiousMeritProductInterface::class, function () {
            return new ReligiousMeritProductCacheDecorator(new ReligiousMeritProductRepository(new ReligiousMeritProduct));
        });

        $this->setNamespace('plugins/religious-merit')->loadHelpers();
    }

    public function boot(): void
    {
        $projectPrefix = get_projects_prefix();
        SlugHelper::registerModule(ReligiousMeritProject::class, 'ReligiousMeritProject');
        SlugHelper::registerModule(ReligiousMeritProjectCategory::class, 'ReligiousMeritProjectCategory');
        // SlugHelper::registerModule(ReligiousMeritProjectCostEstimation::class, 'ReligiousMeritProjectCostEstimation');

        SlugHelper::setPrefix(ReligiousMeritProject::class, $projectPrefix, false);
        SlugHelper::setPrefix(ReligiousMeritProjectCategory::class, $projectPrefix, false);
        // SlugHelper::setPrefix(ReligiousMeritProjectCostEstimation::class, 'project-cost-estimations', true);

        $this
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes();

        $this->app->register(EventServiceProvider::class);

        $this->app->booted(function () {
            \Gallery::registerModule(ReligiousMeritProject::class);
            Form::component('projectCostEstimationRepeater', 'plugins/religious-merit::forms.partials.project-cost-estimation-repeater', [
                'name',
                'value' => null,
                'fields' => [],
                'attributes' => [],
            ]);
            add_filter(BASE_FILTER_REGISTER_CONTENT_TABS, [$this, 'addCostEstimationTab'], 55, 3);
            add_filter(BASE_FILTER_REGISTER_CONTENT_TAB_INSIDE, [$this, 'addCostEstimationContent'], 55, 3);

            $this->app->register(HookServiceProvider::class);
        });

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-plugins-religious-merit',
                'priority' => 5,
                'parent_id' => null,
                'name' => 'plugins/religious-merit::religious-merit.name',
                'icon' => 'fa fa-list',
                'url' => route('religious-merit.index'),
                'permissions' => ['religious.index'],
            ]);
            // Danh mục dự án
            dashboard_menu()->registerItem([
                'id' => 'cms-plugins-religious-merit-project-category',
                'priority' => 1,
                'parent_id' => 'cms-plugins-religious-merit',
                'name' => 'plugins/religious-merit::religious-merit.categories',
                'icon' => null,
                'url' => route('religious-merit-project-category.index'),
                'permissions' => ['religious-merit-project-category.index'],
            ]);
            // Dự án
            dashboard_menu()->registerItem([
                'id' => 'cms-plugins-religious-merit-project',
                'priority' => 2,
                'parent_id' => 'cms-plugins-religious-merit',
                'name' => 'plugins/religious-merit::religious-merit.projects',
                'icon' => null,
                'url' => route('religious-merit-project.index'),
                'permissions' => ['religious-merit-project.index'],
            ]);
            // Công đức
            dashboard_menu()->registerItem([
                'id' => 'cms-plugins-religious-merit-merits',
                'priority' => 4,
                'parent_id' => 'cms-plugins-religious-merit',
                'name' => 'plugins/religious-merit::religious-merit.merits',
                'icon' => null,
                'url' => route('religious-merit.index'),
                'permissions' => ['religious-merit.index'],
            ]);

            dashboard_menu()->registerItem([
                'id' => 'cms-plugins-religious-merit-setting',
                'priority' => 999,
                'parent_id' => 'cms-core-settings',
                'name' => 'plugins/religious-merit::religious-merit.name',
                'icon' => null,
                'url' => route('settings.religious-merit'),
                'permissions' => ['religious-merit.settings'],
            ]);
        });
    }
}
