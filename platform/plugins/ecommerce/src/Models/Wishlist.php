<?php

namespace Cmat\Ecommerce\Models;

use Cmat\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Wishlist extends BaseModel
{
    protected $table = 'ec_wish_lists';

    protected $fillable = [
        'customer_id',
        'product_id',
    ];

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id')->withDefault();
    }
}
