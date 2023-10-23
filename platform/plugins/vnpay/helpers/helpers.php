<?php

use Cmat\Vnpay\Repositories\Interfaces\VnpayInterface;

if (! function_exists('create_vnpay_transaction')) {
    function create_vnpay_transaction($data, $return_url)
    {
        return app(VnpayInterface::class)->creatVnpayTransaction($data, $return_url);
    }
}
