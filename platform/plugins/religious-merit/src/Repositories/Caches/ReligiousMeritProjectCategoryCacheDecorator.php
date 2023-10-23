<?php

namespace Cmat\ReligiousMerit\Repositories\Caches;

use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProjectCategoryInterface;

class ReligiousMeritProjectCategoryCacheDecorator extends CacheAbstractDecorator implements ReligiousMeritProjectCategoryInterface
{
    public function getProjectCategories($keyword = '', $limit = 10, $paginate = 10)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getAllProjectCategories($keyword = '', $orderBy = [], array $select = ['*'])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getProjectCategoryDetail($id)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
