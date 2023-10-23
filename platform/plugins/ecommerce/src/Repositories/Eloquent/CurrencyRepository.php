<?php

namespace Cmat\Ecommerce\Repositories\Eloquent;

use Cmat\Ecommerce\Repositories\Interfaces\CurrencyInterface;
use Cmat\Support\Repositories\Eloquent\RepositoriesAbstract;

class CurrencyRepository extends RepositoriesAbstract implements CurrencyInterface
{
    public function getAllCurrencies()
    {
        $data = $this->model
            ->orderBy('order', 'ASC');

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
