<?php

namespace Cmat\Ecommerce\Supports;

use Cmat\Ecommerce\Models\Product;
use Cmat\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Illuminate\Support\Collection;

class FlashSaleSupport
{
    protected Collection|array $flashSales = [];

    public function flashSaleForProduct(Product $product): ?Product
    {
        if (! $this->flashSales) {
            $this->getAvailableFlashSales();
        }

        if (! $product->id) {
            return null;
        }

        foreach ($this->flashSales as $flashSale) {
            $productId = $product->id;
            if ($product->is_variation) {
                $productId = $product->original_product->id;
            }

            foreach ($flashSale->products as $flashSaleProduct) {
                if ($productId == $flashSaleProduct->id) {
                    return $flashSaleProduct;
                }
            }
        }

        return null;
    }

    public function getAvailableFlashSales(): Collection
    {
        if (! $this->flashSales instanceof Collection) {
            $this->flashSales = collect();
        }

        if ($this->flashSales->count() == 0) {
            $this->flashSales = app(FlashSaleInterface::class)->getAvailableFlashSales(['products']);
        }

        return $this->flashSales;
    }
}
