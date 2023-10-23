<?php

namespace Cmat\Ecommerce\Listeners;

use Cmat\Ecommerce\Events\ProductViewed;
use Cmat\Ecommerce\Models\ProductView;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateProductView implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ProductViewed $event): void
    {
        try {
            ProductView::upsert(
                [
                    'product_id' => $event->product->id,
                    'date' => $event->dateTime->toDateString(),
                    'views' => 1,
                ],
                ['product_id', 'date'],
                ['views' => DB::raw('views + 1')],
            );
        } catch (Throwable $exception) {
            info($exception->getMessage());
        }
    }
}
