<?php

namespace Cmat\Ecommerce\Models;

use Cmat\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariationItem extends BaseModel
{
    protected $table = 'ec_product_variation_items';

    protected $fillable = [
        'attribute_id',
        'variation_id',
    ];

    public $timestamps = false;

    public function productVariation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id')->withDefault();
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id')->withDefault();
    }

    public function attributeSet(): HasMany
    {
        return $this->hasMany(ProductAttributeSet::class, 'attribute_set_id');
    }
}
