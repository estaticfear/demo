<?php

namespace Cmat\ReligiousMerit\Repositories\Eloquent;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\ReligiousMerit\Enums\PaymentGateTypeEnum;
use Cmat\ReligiousMerit\Enums\ReligiousMeritStatusEnum;
use Cmat\ReligiousMerit\Enums\ReligiousTypeEnum;
use Cmat\Support\Repositories\Eloquent\RepositoriesAbstract;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProjectInterface;
use Illuminate\Support\Facades\DB;
use Cmat\ReligiousMerit\Models\ReligiousMerit;

class ReligiousMeritProjectRepository extends RepositoriesAbstract implements ReligiousMeritProjectInterface
{
    public function getAvailableProjects($keyword = '', $limit = 10, $paginate = 10)
    {
        $currentDate = \Carbon\Carbon::now();
        $data = $this->model
        ->whereRaw('current_amount < expectation_amount')
        ->where([
            ['status', '=', BaseStatusEnum::PUBLISHED],
            ['to_date', '>=', $currentDate],
            ['start_date', '<', $currentDate]
        ])
        ->orderBy('order', 'DESC')
        ->orderBy('created_at', 'desc');;

        if ($limit) {
            $data = $data->limit($limit);
        }

        if ($paginate) {
            return $this->applyBeforeExecuteQuery($data)->paginate($paginate);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getFeaturedProjects($keyword = '', $limit = 10, $paginate = 10)
    {
        $currentDate = \Carbon\Carbon::now();
        $data = $this->model
        ->whereRaw('current_amount < expectation_amount')
        ->where([
            ['status', '=', BaseStatusEnum::PUBLISHED],
            ['to_date', '>=', $currentDate],
            ['start_date', '<', $currentDate]
        ])
        ->where('is_featured', 1)
        ->orderBy('order', 'DESC')
        ->orderBy('created_at', 'desc');;

        if ($limit) {
            $data = $data->limit($limit);
        }

        if ($paginate) {
            return $this->applyBeforeExecuteQuery($data)->paginate($paginate);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getFinishedProjects($keyword = '', $limit = 10, $paginate = 10)
    {
        $currentDate = \Carbon\Carbon::now();
        $data = $this->model
        ->where('status', BaseStatusEnum::PUBLISHED)
        ->where(function($query) use ($currentDate) {
            $query->whereRaw('current_amount >= expectation_amount')
                ->orWhere([
                    ['to_date', '<', $currentDate]
                ]);
        })
        ->orderBy('order', 'DESC')
        ->orderBy('created_at', 'desc');;

        if ($limit) {
            $data = $data->limit($limit);
        }

        if ($paginate) {
            return $this->applyBeforeExecuteQuery($data)->paginate($paginate);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getAvailableProjectsByCategory($categoryId, $keyword = '', $limit = 10, $paginate = 10)
    {
        // $currentDate = \Carbon\Carbon::now();
        $data = $this->model
        ->where('status', BaseStatusEnum::PUBLISHED)
        ->where('project_category_id', $categoryId)
        // ->where(function($query) use ($currentDate) {
        //     $query->whereRaw('current_amount < expectation_amount')
        //         ->orWhere([
        //             ['to_date', '<', $currentDate]
        //         ]);
        // })
        ->orderBy('order', 'DESC')
        ->orderBy('created_at', 'desc');;

        if ($limit) {
            $data = $data->limit($limit);
        }

        if ($paginate) {
            return $this->applyBeforeExecuteQuery($data)->paginate($paginate);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getAvailableProjectDetail($id)
    {
        $data = $this->model->where(['id' => $id]);

        return $this->applyBeforeExecuteQuery($data)->first();
    }

    public function getProjectsRelated($project, $limit = 3)
    {
        $currentDate = \Carbon\Carbon::now();
        $data = $this->model
            ->where('id', '!=', $project->id)
            ->whereRaw('current_amount < expectation_amount')
            ->where([
                ['status', '=', BaseStatusEnum::PUBLISHED],
                ['to_date', '>=', $currentDate],
                ['start_date', '<', $currentDate]
            ])
            ->whereHas('category', function ($query) use ($project) {
                $query->where('project_category_id', $project->project_category_id);
            })
            ->orderBy('order', 'DESC')
            ->orderBy('created_at', 'desc')
            ->limit($limit);

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    /**
     * @return mixed
     */
    public function getAllActiveProjectsLabels()
    {
        $currentDate = \Carbon\Carbon::now();
        $projects = $this->all();
        $projects = $this->model
        ->where('status', BaseStatusEnum::PUBLISHED)
        ->where(function($query) use ($currentDate) {
            $query->whereRaw('current_amount >= expectation_amount')
                ->orWhere([
                    ['to_date', '<', $currentDate]
                ]);
        });

        $projects_labels = [];

        foreach ($projects as $project) {
            $projects_labels[$project->id] = $project->name;
        }

        return $projects_labels;
    }

    public function updateProgress($project_id)
    {
        // Todo: đưa vào queue xử lý
        $query = 'UPDATE religious_merit_projects
                    SET current_amount = (
                            SELECT sum(amount)
                            FROM religious_merits
                            WHERE project_id = ?
                        )
                    WHERE id=?;';
        DB::update($query, [$project_id, $project_id]);
    }

    /**
     * @return mixed
     */
    public function getProjectMerits($project_id, $query = '', $type = '', $limit = 10, $paginate = 10)
    {
        $data = ReligiousMerit::where('project_id', $project_id)->where('status', ReligiousMeritStatusEnum::SUCCESS);
        if ($query) {
            $data->where('name', 'like', '%' . $query . '%');
        };

        if ($type === ReligiousTypeEnum::EFFORT || $type === ReligiousTypeEnum::ARTIFACT) {
            $data->where('type', $type);
        };

        if ($type === PaymentGateTypeEnum::CASH || $type === PaymentGateTypeEnum::TRANSFER) {
            $data->where([
                'type' => ReligiousTypeEnum::MONEY,
                'payment_gate' => $type
            ]);
        };

        if ($limit) {
            $data = $data->limit($limit);
        }

       return $this->applyBeforeExecuteQuery($data)->paginate($paginate);
    }
}
