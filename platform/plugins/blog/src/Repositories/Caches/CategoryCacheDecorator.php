<?php

namespace Cmat\Blog\Repositories\Caches;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Blog\Models\Category;
use Cmat\Blog\Repositories\Interfaces\CategoryInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;
use Eloquent;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CategoryCacheDecorator extends CacheAbstractDecorator implements CategoryInterface
{
    public function getDataSiteMap(): Collection
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getFeaturedCategories(?int $limit, array $with = []): Collection
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getAllCategories(array $condition = [], array $with = []): Collection
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getCategoryById(int|string|null $id): ?Category
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    public function getCategories(array $select, array $orderBy, array $conditions = ['status' => BaseStatusEnum::PUBLISHED]): Collection
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getAllRelatedChildrenIds(int|string|null|Eloquent $id): array
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getAllCategoriesWithChildren(array $condition = [], array $with = [], array $select = ['*']): Collection
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getFilters(array $filters): LengthAwarePaginator
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getPopularCategories(int $limit, array $with = ['slugable'], array $withCount = ['posts']): Collection
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
