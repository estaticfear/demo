<?php

namespace Cmat\Ecommerce\Events;

use Cmat\Base\Events\Event;
use Cmat\Ecommerce\Models\OrderReturn;
use Illuminate\Queue\SerializesModels;

class OrderReturnedEvent extends Event
{
    use SerializesModels;

    public OrderReturn $order;

    public function __construct(OrderReturn $order)
    {
        $this->order = $order;
    }
}
