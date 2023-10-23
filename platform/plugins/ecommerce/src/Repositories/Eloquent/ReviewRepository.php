<?php

namespace Cmat\Ecommerce\Repositories\Eloquent;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Ecommerce\Repositories\Interfaces\ReviewInterface;
use Cmat\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Support\Facades\DB;

class ReviewRepository extends RepositoriesAbstract implements ReviewInterface
{
    public function getGroupedByProductId($productId)
    {
        $data = $this->model
            ->select([DB::raw('COUNT(star) as star_count'), 'star'])
            ->where([
                'product_id' => $productId,
                'status' => BaseStatusEnum::PUBLISHED,
            ])
            ->groupBy('star');

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
