<?php

namespace Cmat\ReligiousMerit\Repositories\Interfaces;

use Cmat\Support\Repositories\Interfaces\RepositoryInterface;

interface ReligiousMeritProjectCategoryInterface extends RepositoryInterface
{
    /**
     * @param string $query
     * @param int $limit
     * @param int $paginate
     * @return mixed
     */
    public function getProjectCategories($query = '', $limit = 10, $paginate = 10);

    /**
     * @param int|string $id
     * @return mixed
     */
    public function getProjectCategoryDetail($id);

    public function getAllProjectCategories($query = '', array $orderBy = [], array $select = ['*']);
}
