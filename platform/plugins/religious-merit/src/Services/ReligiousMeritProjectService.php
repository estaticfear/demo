<?php

namespace Cmat\ReligiousMerit\Services;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Supports\Helper;
use Cmat\ReligiousMerit\Models\ReligiousMerit;
use Cmat\ReligiousMerit\Models\ReligiousMeritProject;
use Cmat\ReligiousMerit\Models\ReligiousMeritProjectCategory;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProjectCategoryInterface;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProjectInterface;
use Cmat\SeoHelper\SeoOpenGraph;
use Cmat\Slug\Models\Slug;
use Eloquent;
use Html;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use RvMedia;
use SeoHelper;
use Theme;

class ReligiousMeritProjectService
{
    public function handleFrontRoutes(Slug|array $slug): Eloquent|array
    {
        if (!$slug instanceof Eloquent) {
            return $slug;
        }

        $condition = [
            'id' => $slug->reference_id,
            'status' => BaseStatusEnum::PUBLISHED,
            // 'reference_type' => ReligiousMeritProject::class
        ];

        if (Auth::check() && request()->input('preview')) {
            Arr::forget($condition, 'status');
        }
        switch ($slug->reference_type) {
            case ReligiousMeritProject::class:
                $project = app(ReligiousMeritProjectInterface::class)
                    ->getFirstBy(
                        $condition,
                        ['*'],
                        [],
                        // ['categories', 'tags', 'slugable', 'categories.slugable', 'tags.slugable']
                    );

                if (empty($project)) {
                    abort(404);
                }

                Helper::handleViewCount($project, 'viewed_post');

                SeoHelper::setTitle($project->name)
                    ->setDescription($project->description);

                $meta = new SeoOpenGraph();
                if ($project->image) {
                    $meta->setImage(RvMedia::getImageUrl($project->image));
                }
                $meta->setDescription($project->description);
                $meta->setUrl($project->url);
                $meta->setTitle($project->name);
                $meta->setType('article');

                SeoHelper::setSeoOpenGraph($meta);

                SeoHelper::meta()->setUrl($project->url);

                if (function_exists('admin_bar') && Auth::check() && Auth::user()->hasPermission('religious-merit-project.edit')) {
                    admin_bar()->registerLink(
                        trans('plugins/religious-merit::religious-merit-project.edit_this_project'),
                        route('religious-merit-project.edit', $project->id),
                        null,
                        'religious-merit-project.edit'
                    );
                }

                Theme::breadcrumb()->add(('Trang chủ'), route('public.index'));
                Theme::breadcrumb()->add(('Dự án'), route('public.religious-merit-project.projects-available'));
                Theme::breadcrumb()->add($project->name, $project->url);

                Theme::asset()->add('ckeditor-content-styles', 'vendor/core/core/base/libraries/ckeditor/content-styles.css');

                $project->content = Html::tag('div', (string)$project->content, ['class' => 'ck-content'])->toHtml();

                $projectsRelated = app(ReligiousMeritProjectInterface::class)->getProjectsRelated($project, 3);

                do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, RELIGIOUS_MERIT_MODULE_SCREEN_NAME, $project);

                return [
                    'view' => 'religious-merit-project-detail',
                    'default_view' => 'plugins/religious-merit::themes.project',
                    'data' => [
                        'project' => $project,
                        'projectsRelated' => $projectsRelated
                    ],
                    'slug' => $project->slug,
                ];
                break;

            case ReligiousMeritProjectCategory::class:
                $category = app(ReligiousMeritProjectCategoryInterface::class)
                    ->getFirstBy($condition, ['*'], ['slugable']);

                if (empty($category)) {
                    abort(404);
                }
                SeoHelper::setTitle($category->name)
                    ->setDescription($category->description);

                $meta = new SeoOpenGraph();
                if ($category->image) {
                    $meta->setImage(RvMedia::getImageUrl($category->image));
                }
                $meta->setDescription($category->description);
                $meta->setUrl($category->url);
                $meta->setTitle($category->name);
                $meta->setType('article');

                SeoHelper::setSeoOpenGraph($meta);

                SeoHelper::meta()->setUrl($category->url);

                if (function_exists('admin_bar')) {
                    admin_bar()->registerLink(
                        trans('plugins/blog::categories.edit_this_category'),
                        route('categories.edit', $category->id),
                        null,
                        'categories.edit'
                    );
                }

                // $allRelatedCategoryIds = array_merge([$category->id], $category->projects->pluck('id')->all());

                $projects = app(ReligiousMeritProjectInterface::class)
                    ->getAvailableProjectsByCategory($category->id, '', 12, 12);

                Theme::breadcrumb()
                    ->add(__('Home'), route('public.index'));

                Theme::breadcrumb()->add($category->name, $category->url);

                do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, RELIGIOUS_MERIT_MODULE_SCREEN_NAME, $category);

                return [
                    'view' => 'religious-merit-project-category-detail',
                    'default_view' => 'plugins/religious-merit::themes.project-category',
                    // 'view' => 'category',
                    // 'default_view' => 'plugins/blog::themes.category',
                    'data' => compact('category', 'projects'),
                    'slug' => $category->slug,
                ];
                break;

            default:
                # code...
                break;
        }

        return $slug;
    }

    public function getProjectBudget(ReligiousMeritProject|null $project, string|null $search, string|int $page = 1, $limit = 10) {
        $budgets = [];
        $totalPages = 0;
        $offset = 0;
        $totalItems = 0;

        if ($project) {
            $budgets = $project->cost_estimations_data;
            if ($search) {
                $arr = array();
                foreach ($budgets as $budget) {
                    foreach ($budget as $item) {
                        if ($item['key'] === 'name' && str_contains($item['value'], $search)) {
                            array_push($arr, $budget);
                        }
                    }
                }
                $budgets = $arr;
            };

            $page = $page ? (int) $page : 1;
            $totalItems = count($budgets);
            $totalPages = ceil($totalItems / $limit);
            $page = max($page, 1);
            $page = min($page, $totalPages);
            $offset = ($page - 1) * $limit;
            if ($offset < 0) $offset = 0;
            $budgets = array_slice($budgets, $offset, $limit);
        }
        return [
            'budgets' => $budgets,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'limit' => $limit,
            'offset' => $offset
        ];
    }

    public function getProjectEfforts(array $efforts, string|null $search, string|int $page = 1, $limit = 10) {
        $totalPages = 0;
        $offset = 0;
        $totalItems = 0;

        if (!empty($efforts)) {
            if ($search) {
                $arr = array();
                foreach ($efforts as $effort) {
                    if (str_contains($effort['product_name'], $search)) {
                        array_push($arr, $effort);
                    }
                }
                $efforts = $arr;
            };

            $page = $page ? (int) $page : 1;
            $totalItems = count($efforts);
            $totalPages = ceil($totalItems / $limit);
            $page = max($page, 1);
            $page = min($page, $totalPages);
            $offset = ($page - 1) * $limit;
            if ($offset < 0) $offset = 0;
            $efforts = array_slice($efforts, $offset, $limit);
        }

        return [
            'efforts' => $efforts,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'limit' => $limit,
            'offset' => $offset
        ];
    }

    public function getProjectArtifacts(array $artifacts, string|null $search, string|int $page = 1, $limit = 10) {
        $totalPages = 0;
        $offset = 0;
        $totalItems = 0;

        if (!empty($artifacts)) {
            if ($search) {
                $arr = array();
                foreach ($artifacts as $artifact) {
                    if (str_contains($artifact['product_name'], $search)) {
                        array_push($arr, $artifact);
                    }
                }
                $artifacts = $arr;
            };

            $page = $page ? (int) $page : 1;
            $totalItems = count($artifacts);
            $totalPages = ceil($totalItems / $limit);
            $page = max($page, 1);
            $page = min($page, $totalPages);
            $offset = ($page - 1) * $limit;
            if ($offset < 0) $offset = 0;
            $artifacts = array_slice($artifacts, $offset, $limit);
        }

        return [
            'artifacts' => $artifacts,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'limit' => $limit,
            'offset' => $offset
        ];
    }
}
