<?php

namespace Cmat\Ecommerce\Supports;

use Cmat\Ecommerce\Models\Product;
use Cmat\Ecommerce\Repositories\Interfaces\ProductInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductVariationItemInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductVariationInterface;

class RenderProductSwatchesSupport
{
    protected Product $product;

    public function __construct(protected ProductInterface $productRepository)
    {
    }

    public function setProduct(Product $product): RenderProductSwatchesSupport
    {
        $this->product = $product;

        return $this;
    }

    public function render(array $params = []): string
    {
        $params = array_merge([
            'selected' => [],
            'view' => 'plugins/ecommerce::themes.attributes.swatches-renderer',
        ], $params);

        $product = $this->product;

        $attributeSets = $product->productAttributeSets()->orderBy('order')->get();

        $attributes = $this->productRepository->getRelatedProductAttributes($this->product)->sortBy('order');

        $productVariations = app(ProductVariationInterface::class)->allBy([
            'configurable_product_id' => $product->id,
        ], ['product', 'productAttributes']);

        $productVariationsInfo = app(ProductVariationItemInterface::class)
            ->getVariationsInfo($productVariations->pluck('id')->toArray());

        $selected = $params['selected'];

        return view($params['view'], compact(
            'attributeSets',
            'attributes',
            'product',
            'selected',
            'productVariationsInfo',
            'productVariations'
        ))->render();
    }
}
