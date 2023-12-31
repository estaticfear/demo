<?php

namespace Cmat\Blog\Repositories\Eloquent;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Blog\Repositories\Interfaces\PostInterface;
use Cmat\Support\Repositories\Eloquent\RepositoriesAbstract;
use Eloquent;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;

class PostRepository extends RepositoriesAbstract implements PostInterface
{
    public function getFeatured(int $limit = 5, array $with = [])
    {
        $data = $this->model
            ->where([
                'status' => BaseStatusEnum::PUBLISHED,
                'is_featured' => 1,
            ])
            ->limit($limit)
            ->with(array_merge(['slugable'], $with))
            ->orderBy('created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getListPostNonInList(array $selected = [], $limit = 7, array $with = [])
    {
        $data = $this->model
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->whereNotIn('id', $selected)
            ->limit($limit)
            ->with($with)
            ->orderBy('created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getRelated($id, $limit = 3)
    {
        $data = $this->model
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->where('id', '!=', $id)
            ->limit($limit)
            ->with('slugable')
            ->orderBy('created_at', 'desc')
            ->whereHas('categories', function ($query) use ($id) {
                $query->whereIn('categories.id', $this->getRelatedCategoryIds($id));
            });

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getRelatedCategoryIds($model)
    {
        $model = $model instanceof Eloquent ? $model : $this->findById($model);

        if (! $model) {
            return [];
        }

        try {
            return $model->categories()->allRelatedIds()->toArray();
        } catch (Exception) {
            return [];
        }
    }

    public function getByCategory($categoryId, $paginate = 12, $limit = 0)
    {
        if (! is_array($categoryId)) {
            $categoryId = [$categoryId];
        }

        $data = $this->model
            ->where('posts.status', BaseStatusEnum::PUBLISHED)
            ->join('post_categories', 'post_categories.post_id', '=', 'posts.id')
            ->join('categories', 'post_categories.category_id', '=', 'categories.id')
            ->whereIn('post_categories.category_id', $categoryId)
            ->select('posts.*')
            ->distinct()
            ->with('slugable')
            ->orderBy('posts.created_at', 'desc');

        if ($paginate != 0) {
            return $this->applyBeforeExecuteQuery($data)->paginate($paginate);
        }

        return $this->applyBeforeExecuteQuery($data)->limit($limit)->get();
    }

    public function getByUserId($authorId, $paginate = 6)
    {
        $data = $this->model
            ->where([
                'status' => BaseStatusEnum::PUBLISHED,
                'author_id' => $authorId,
            ])
            ->with('slugable')
            ->orderBy('created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data)->paginate($paginate);
    }

    public function getDataSiteMap()
    {
        $data = $this->model
            ->with('slugable')
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getByTag($tag, $paginate = 12)
    {
        $data = $this->model
            ->with('slugable', 'categories', 'categories.slugable', 'author')
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->whereHas('tags', function ($query) use ($tag) {
                /**
                 * @var Builder $query
                 */
                $query->where('tags.id', $tag);
            })
            ->orderBy('created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data)->paginate($paginate);
    }

    public function getRecentPosts($limit = 5, $categoryId = 0)
    {
        $data = $this->model->where(['status' => BaseStatusEnum::PUBLISHED]);

        if ($categoryId != 0) {
            $data = $data
                ->join('post_categories', 'post_categories.post_id', '=', 'posts.id')
                ->where('post_categories.category_id', $categoryId);
        }

        $data = $data->limit($limit)
            ->with('slugable')
            ->select('posts.*')
            ->orderBy('created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getSearch($keyword, $limit = 10, $paginate = 10)
    {
        $data = $this->model
            ->with('slugable')
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->where(function ($query) use ($keyword) {
                $query->addSearch('name', $keyword);
            })
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $data = $data->limit($limit);
        }

        if ($paginate) {
            return $this->applyBeforeExecuteQuery($data)->paginate($paginate);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getAllPosts($perPage = 12, $active = true, array $with = ['slugable'])
    {
        $data = $this->model
            ->with($with)
            ->orderBy('created_at', 'desc');

        if ($active) {
            $data = $data->where('status', BaseStatusEnum::PUBLISHED);
        }

        return $this->applyBeforeExecuteQuery($data)->paginate($perPage);
    }

    public function getPopularPosts($limit, array $args = [])
    {
        $data = $this->model
            ->with('slugable')
            ->orderBy('views', 'desc')
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->limit($limit);

        if (! empty(Arr::get($args, 'where'))) {
            $data = $data->where($args['where']);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getFilters(array $filters)
    {
        $data = $this->originalModel;

        if ($filters['categories'] !== null) {
            $categories = array_filter((array)$filters['categories']);

            $data = $data->whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('categories.id', $categories);
            });
        }

        if ($filters['categories_exclude'] !== null) {
            $data = $data
                ->whereHas('categories', function ($query) use ($filters) {
                    $query->whereNotIn('categories.id', array_filter((array)$filters['categories_exclude']));
                });
        }

        if ($filters['exclude'] !== null) {
            $data = $data->whereNotIn('posts.id', array_filter((array)$filters['exclude']));
        }

        if ($filters['include'] !== null) {
            $data = $data->whereNotIn('posts.id', array_filter((array)$filters['include']));
        }

        if ($filters['author'] !== null) {
            $data = $data->whereIn('author_id', array_filter((array)$filters['author']));
        }

        if ($filters['author_exclude'] !== null) {
            $data = $data->whereNotIn('author_id', array_filter((array)$filters['author_exclude']));
        }

        if ($filters['featured'] !== null) {
            $data = $data->where('posts.is_featured', $filters['featured']);
        }

        if ($filters['search'] !== null) {
            $data = $data
                ->where(function ($query) use ($filters) {
                    $query
                        ->addSearch('posts.name', $filters['search'])
                        ->addSearch('posts.description', $filters['search']);
                });
        }

        $orderBy = Arr::get($filters, 'order_by', 'updated_at');
        $order = Arr::get($filters, 'order', 'desc');

        $data = $data
            ->where('posts.status', BaseStatusEnum::PUBLISHED)
            ->orderBy($orderBy, $order);

        return $this->applyBeforeExecuteQuery($data)->paginate((int)$filters['per_page']);
    }

    public function getFeaturedAndOtherPostsByCategory($categoryId, $paginate = 12, $limit = 0)
    {
        if (! is_array($categoryId)) {
            $categoryId = [$categoryId];
        }

        $featuredPosts = $this->model
            ->where('posts.status', BaseStatusEnum::PUBLISHED)
            ->where('posts.is_featured', 1)
            ->join('post_categories', 'post_categories.post_id', '=', 'posts.id')
            ->join('categories', 'post_categories.category_id', '=', 'categories.id')
            ->whereIn('post_categories.category_id', $categoryId)
            ->select('posts.*')
            ->limit(7)
            ->with(array_merge(['slugable']))
            ->orderBy('updated_at', 'desc')
            ->get();

        $featuredPostIds = $featuredPosts->pluck('id');

        $otherPosts = $this->model
            ->where('posts.status', BaseStatusEnum::PUBLISHED)
            ->whereNotIn('posts.id', $featuredPostIds)
            ->join('post_categories', 'post_categories.post_id', '=', 'posts.id')
            ->join('categories', 'post_categories.category_id', '=', 'categories.id')
            ->whereIn('post_categories.category_id', $categoryId)
            ->select('posts.*')
            ->distinct()
            ->with('slugable')
            ->orderBy('posts.created_at', 'desc');

        $otherPosts = $this->applyBeforeExecuteQuery($otherPosts)->paginate($paginate);

        return [
            'featured' => $featuredPosts,
            'other' => $otherPosts
        ];
    }
}
