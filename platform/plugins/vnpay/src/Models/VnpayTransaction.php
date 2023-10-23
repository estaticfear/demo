<?php

namespace Cmat\Vnpay\Models;

use Cmat\Base\Models\BaseModel;
use Cmat\Vnpay\Enums\VnpayTransactionStatusEnum;

class VnpayTransaction extends BaseModel
{
    protected $table = 'vnpay_transactions';

    protected $fillable = [
        'name',
        'target_id',
        'target_type',
        'amount',
        'language',
        'ip',
        'order_type',
        'status',
        'bank_code',
        'message',
        'transaction_no',
        'transaction_status',
        'transaction_type',
    ];

    protected $casts = [
        'status' => VnpayTransactionStatusEnum::class,
    ];
}
