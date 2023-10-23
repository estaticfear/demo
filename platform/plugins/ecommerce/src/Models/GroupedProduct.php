<?php

namespace Cmat\Ecommerce\Models;

use Cmat\Base\Models\BaseModel;

class GroupedProduct extends BaseModel
{
    protected $table = 'ec_grouped_products';

    protected $fillable = [
        'parent_product_id',
        'product_id',
        'fixed_qty',
    ];

    public $timestamps = false;
}
