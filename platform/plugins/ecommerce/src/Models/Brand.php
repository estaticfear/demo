<?php

namespace Cmat\Ecommerce\Models;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends BaseModel
{
    protected $table = 'ec_brands';

    protected $fillable = [
        'name',
        'website',
        'logo',
        'description',
        'order',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function products(): HasMany
    {
        return $this
            ->hasMany(Product::class, 'brand_id')
            ->where('is_variation', 0)
            ->where('status', BaseStatusEnum::PUBLISHED);
    }
}
