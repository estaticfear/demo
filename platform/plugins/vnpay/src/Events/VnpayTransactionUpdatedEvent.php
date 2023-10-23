<?php

namespace Cmat\Vnpay\Events;

use Cmat\Base\Events\Event;
use Eloquent;
use Illuminate\Queue\SerializesModels;

class VnpayTransactionUpdatedEvent extends Event
{
    use SerializesModels;

    /**
     * @var Eloquent|false
     */
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
}
