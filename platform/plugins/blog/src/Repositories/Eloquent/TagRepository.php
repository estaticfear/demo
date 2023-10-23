<?php

namespace Cmat\Blog\Repositories\Eloquent;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Blog\Repositories\Interfaces\TagInterface;
use Cmat\Support\Repositories\Eloquent\RepositoriesAbstract;

class TagRepository extends RepositoriesAbstract implements TagInterface
{
    public function getDataSiteMap()
    {
        $data = $this->model
            ->with('slugable')
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('created_at', 'desc')
            ->select(['id', 'name', 'updated_at']);

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getPopularTags($limit, array $with = ['slugable'], array $withCount = ['posts'])
    {
        $data = $this->model
            ->with($with)
            ->withCount($withCount)
            ->orderBy('posts_count', 'DESC')
            ->limit($limit);

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getAllTags($active = true)
    {
        $data = $this->model;
        if ($active) {
            $data = $data->where('status', BaseStatusEnum::PUBLISHED);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
