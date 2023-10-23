<?php

namespace Cmat\Faq\Repositories\Eloquent;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Support\Repositories\Eloquent\RepositoriesAbstract;
use Cmat\Faq\Repositories\Interfaces\FaqInterface;

class FaqRepository extends RepositoriesAbstract implements FaqInterface
{
    public function getAllActiveFaqs($perPage = 12)
    {
        $data = $this->model
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data)->paginate($perPage);
    }
}
