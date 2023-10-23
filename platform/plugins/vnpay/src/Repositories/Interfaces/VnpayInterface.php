<?php

namespace Cmat\Vnpay\Repositories\Interfaces;

use Cmat\Support\Repositories\Interfaces\RepositoryInterface;

interface VnpayInterface extends RepositoryInterface
{
    public function creatVnpayTransaction($data, $return_url);
}
