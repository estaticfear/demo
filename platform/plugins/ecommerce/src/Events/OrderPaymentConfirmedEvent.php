<?php

namespace Cmat\Ecommerce\Events;

use Cmat\ACL\Models\User;
use Cmat\Base\Events\Event;
use Cmat\Ecommerce\Models\Order;
use Illuminate\Queue\SerializesModels;

class OrderPaymentConfirmedEvent extends Event
{
    use SerializesModels;

    public Order $order;

    public User $confirmedBy;

    public function __construct(Order $order, User $confirmedBy)
    {
        $this->order = $order;
        $this->confirmedBy = $confirmedBy;
    }
}
