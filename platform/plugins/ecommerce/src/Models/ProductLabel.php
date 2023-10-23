<?php

namespace Cmat\Ecommerce\Models;

use Cmat\Base\Casts\SafeContent;
use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Models\BaseModel;

class ProductLabel extends BaseModel
{
    protected $table = 'ec_product_labels';

    protected $fillable = [
        'name',
        'color',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
    ];
}
