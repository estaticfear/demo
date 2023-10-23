<?php

namespace Cmat\ReligiousMerit\Models;

use Cmat\Base\Models\BaseModel;
use Cmat\Ecommerce\Enums\ProductTypeEnum;
use Cmat\Ecommerce\Models\Product;
use Cmat\ReligiousMerit\Models\ReligiousMeritProject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReligiousMeritProjectProduct extends BaseModel
{
    // Bảng chứa danh sách các sản phẩm được đưa vào dự án phục vụ đóng góp bằng hiện vật/công sức
    protected $table = 'religious_merit_project_product';

    protected $fillable = [
        'merit_project_id',
        'product_id',
        'product_name',
        'qty',
        'total_merit_qty',
        'price',
        'product_type',
        'is_not_allow_merit_a_part'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withDefault();
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(ReligiousMeritProject::class)->withDefault();
    }


    public function isTypeDigital(): bool
    {
        return isset($this->attributes['product_type']) && $this->attributes['product_type'] == ProductTypeEnum::DIGITAL;
    }
}
