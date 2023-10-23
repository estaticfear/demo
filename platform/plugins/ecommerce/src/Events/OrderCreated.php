<?php

namespace Cmat\Ecommerce\Events;

use Cmat\Base\Events\Event;
use Cmat\Ecommerce\Models\Order;
use Illuminate\Queue\SerializesModels;

class OrderCreated extends Event
{
    use SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
