<?php

namespace Cmat\Ecommerce\Listeners;

use Cmat\Ecommerce\Events\OrderCreated;
use Cmat\Ecommerce\Events\OrderPlacedEvent;
use InvoiceHelper;

class GenerateInvoiceListener
{
    public function handle(OrderPlacedEvent|OrderCreated $event): void
    {
        $order = $event->order;

        InvoiceHelper::store($order);
    }
}
