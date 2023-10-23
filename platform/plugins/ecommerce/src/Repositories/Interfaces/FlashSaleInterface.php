<?php

namespace Cmat\Ecommerce\Repositories\Interfaces;

use Cmat\Support\Repositories\Interfaces\RepositoryInterface;

interface FlashSaleInterface extends RepositoryInterface
{
    /**
     * @param array $with
     * @return mixed
     */
    public function getAvailableFlashSales(array $with = []);
}
