<?php

namespace Cmat\Ecommerce\Events;

use Cmat\Base\Events\Event;
use Cmat\Ecommerce\Models\Shipment;
use Illuminate\Queue\SerializesModels;

class ShippingStatusChanged extends Event
{
    use SerializesModels;

    public function __construct(public Shipment $shipment, public array $previousShipment = [])
    {
    }
}
