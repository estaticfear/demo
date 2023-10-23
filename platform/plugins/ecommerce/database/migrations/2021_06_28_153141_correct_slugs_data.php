<?php

use Cmat\Ecommerce\Models\Product;
use Cmat\Slug\Models\Slug;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        Slug::join('ec_products', 'ec_products.id', '=', 'slugs.reference_id')
            ->where('reference_type', Product::class)
            ->where('ec_products.is_variation', 1)
            ->delete();
    }
};
