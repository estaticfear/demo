<?php

namespace Cmat\Ecommerce\Repositories\Interfaces;

use Cmat\Support\Repositories\Interfaces\RepositoryInterface;

interface ReviewInterface extends RepositoryInterface
{
    /**
     * @param int $productId
     * @return mixed
     */
    public function getGroupedByProductId(array $productId);
}
