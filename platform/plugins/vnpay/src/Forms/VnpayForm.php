<?php

namespace Cmat\Vnpay\Forms;

use Cmat\Base\Forms\FormAbstract;
use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Vnpay\Http\Requests\VnpayRequest;
use Cmat\Vnpay\Models\VnpayTransaction;

class VnpayForm extends FormAbstract
{
    public function buildForm(): void
    {
        $this
            ->setupModel(new VnpayTransaction);
    }
}
