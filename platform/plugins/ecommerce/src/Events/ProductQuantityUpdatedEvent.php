<?php

namespace Cmat\Ecommerce\Events;

use Cmat\Base\Events\Event;
use Cmat\Ecommerce\Models\Product;
use Illuminate\Queue\SerializesModels;

class ProductQuantityUpdatedEvent extends Event
{
    use SerializesModels;

    public Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}
