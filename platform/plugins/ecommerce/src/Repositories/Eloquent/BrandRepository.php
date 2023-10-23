<?php

namespace Cmat\Ecommerce\Repositories\Eloquent;

use Cmat\Ecommerce\Repositories\Interfaces\BrandInterface;
use Cmat\Support\Repositories\Eloquent\RepositoriesAbstract;

class BrandRepository extends RepositoriesAbstract implements BrandInterface
{
    public function getAll(array $condition = [])
    {
        $data = $this->model
            ->where($condition)
            ->orderBy('is_featured', 'DESC')
            ->orderBy('name', 'ASC');

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
