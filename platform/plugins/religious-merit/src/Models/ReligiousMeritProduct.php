<?php

namespace Cmat\ReligiousMerit\Models;

use Cmat\Base\Models\BaseModel;
use Cmat\ReligiousMerit\Models\ReligiousMeritProjectProduct;
use Cmat\ReligiousMerit\Models\ReligiousMerit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReligiousMeritProduct extends BaseModel
{
    // Bảng chứa danh sách các sản phẩm khi đóng góp bằng hiện vật/công sức
    protected $table = 'religious_merit_products';

    protected $fillable = [
        'merit_id',
        'merit_project_product_id',
        'qty',
        'price',
        'product_name'
    ];

    public function meritProjectProduct(): BelongsTo
    {
        return $this->belongsTo(ReligiousMeritProjectProduct::class, 'merit_project_product_id')->with('product');
    }

    public function merit(): BelongsTo
    {
        return $this->belongsTo(ReligiousMerit::class)->withDefault();
    }
}
