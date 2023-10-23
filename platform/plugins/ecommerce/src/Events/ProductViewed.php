<?php

namespace Cmat\Ecommerce\Events;

use Cmat\Base\Events\Event;
use Cmat\Ecommerce\Models\Product;
use Carbon\CarbonInterface;
use Illuminate\Queue\SerializesModels;

class ProductViewed extends Event
{
    use SerializesModels;

    public function __construct(public Product $product, public CarbonInterface $dateTime)
    {
    }
}
