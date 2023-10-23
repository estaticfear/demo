<?php

namespace Cmat\ReligiousMerit\Repositories\Eloquent;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Support\Repositories\Eloquent\RepositoriesAbstract;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProjectCategoryInterface;

class ReligiousMeritProjectCategoryRepository extends RepositoriesAbstract implements ReligiousMeritProjectCategoryInterface
{
    public function getProjectCategories($keyword = '', $limit = 10, $paginate = 10)
    {
        $data = $this->model->where(['status' => BaseStatusEnum::PUBLISHED,]);

        if ($keyword) {
            $data->where(function ($query) use ($keyword) {
                $query->addSearch('name', $keyword);
            });
        }

        if ($limit) {
            $data = $data->limit($limit);
        }

        if ($paginate) {
            return $this->applyBeforeExecuteQuery($data)->paginate($paginate);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getAllProjectCategories($keyword = '', $orderBy = [], array $select = ['*'])
    {
        $data = $this->model->with('slugable');
        // ->where(['status' => BaseStatusEnum::PUBLISHED])

        if ($keyword) {
            $data->where(function ($query) use ($keyword) {
                $query->addSearch('name', $keyword);
            });
        }

        foreach ($orderBy as $by => $direction) {
            $data = $data->orderBy($by, $direction);
        }

        $data = $data->select($select);

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getProjectCategoryDetail($id)
    {
        $data = $this->model->where(['id' => $id, 'status' => BaseStatusEnum::PUBLISHED]);

        return $this->applyBeforeExecuteQuery($data)->first();
    }
}
