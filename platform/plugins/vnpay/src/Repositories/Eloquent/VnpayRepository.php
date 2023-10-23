<?php

namespace Cmat\Vnpay\Repositories\Eloquent;

use Cmat\Support\Repositories\Eloquent\RepositoriesAbstract;
use Cmat\Vnpay\Repositories\Interfaces\VnpayInterface;
use Cmat\Vnpay\Supports\Helper;
use Illuminate\Database\Eloquent\Model;

class VnpayRepository extends RepositoriesAbstract implements VnpayInterface
{
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    public function creatVnpayTransaction($data, $return_url)
    {
        $new_transaction = $this->create($data);

        $payment_url = Helper::generatePaymentURL($new_transaction, $return_url);

        return [
            'transaction' => $new_transaction,
            'payment_url' =>$payment_url
        ];
    }
}
