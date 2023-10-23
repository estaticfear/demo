<?php

namespace Cmat\Contact\Repositories\Eloquent;

use Cmat\Contact\Enums\ContactStatusEnum;
use Cmat\Contact\Repositories\Interfaces\ContactInterface;
use Cmat\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Eloquent\Collection;

class ContactRepository extends RepositoriesAbstract implements ContactInterface
{
    public function getUnread(array $select = ['*']): Collection
    {
        $data = $this->model
            ->where('status', ContactStatusEnum::UNREAD)
            ->select($select)
            ->orderBy('created_at', 'DESC')
            ->get();

        $this->resetModel();

        return $data;
    }

    public function countUnread(): int
    {
        $data = $this->model->where('status', ContactStatusEnum::UNREAD)->count();
        $this->resetModel();

        return $data;
    }
}
