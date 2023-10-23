<?php

namespace Cmat\ReligiousMerit\Repositories\Eloquent;

use Cmat\ReligiousMerit\Enums\ReligiousMeritStatusEnum;
use Cmat\Support\Repositories\Eloquent\RepositoriesAbstract;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ReligiousMeritRepository extends RepositoriesAbstract implements ReligiousMeritInterface
{
    public function updatePaymentStatus(string $id, ReligiousMeritStatusEnum $status)
    {
        return $this->update([
            'id' => $id
        ], [
            'status' => $status
        ]);
    }

    public function getMyMerits($limit, $keyword = '', array $orderBy = ['id' => 'DESC'], array $conditions = []): LengthAwarePaginator
    {
        $member = auth('member')->user();

        if (!$member) {
            return  null;
        }

        $conditions = [
            'member_id' => $member->id,
            ...$conditions
        ];

        $data = $this->model->where($conditions)->whereIn('status', [ReligiousMeritStatusEnum::SUCCESS, ReligiousMeritStatusEnum::IS_BOOKED])->with(['project']);

        if ($keyword) {
            $data = $data->whereRelation('project', 'name', 'like', '%' . $keyword . '%');
        }

        foreach ($orderBy as $by => $direction) {
            $data = $data->orderBy($by, $direction);
        }

        return $this->applyBeforeExecuteQuery($data)->paginate(($limit));
    }

    public function getMyMeritsReport()
    {
        $member = auth('member')->user();

        if (!$member) {
            return  null;
        }

        $conditions = [
            'member_id' => $member->id,
        ];
        $data = $this->model
            ->where($conditions)
            ->whereIn('status', [ReligiousMeritStatusEnum::SUCCESS, ReligiousMeritStatusEnum::IS_BOOKED])
            ->selectRaw('sum(amount) as total_amount, count(DISTINCT project_id) as total_project')
            ->first()
            ->toArray();

        return $data;
    }
}
