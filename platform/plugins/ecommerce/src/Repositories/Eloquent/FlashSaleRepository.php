<?php

namespace Cmat\Ecommerce\Repositories\Eloquent;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Cmat\Support\Repositories\Eloquent\RepositoriesAbstract;

class FlashSaleRepository extends RepositoriesAbstract implements FlashSaleInterface
{
    public function getAvailableFlashSales(array $with = [])
    {
        $data = $this->model
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->notExpired()
            ->latest();

        if ($with) {
            $data = $data->with($with);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
