<?php

namespace Cmat\Ecommerce\Models;

use Cmat\Base\Models\BaseModel;
use Cmat\Ecommerce\Enums\ShippingCodStatusEnum;
use Cmat\Ecommerce\Enums\ShippingStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shipment extends BaseModel
{
    protected $table = 'ec_shipments';

    protected $fillable = [
        'order_id',
        'user_id',
        'weight',
        'shipment_id',
        'rate_id',
        'note',
        'status',
        'cod_amount',
        'cod_status',
        'cross_checking_status',
        'price',
        'store_id',
        'tracking_id',
        'shipping_company_name',
        'tracking_link',
        'estimate_date_shipped',
        'date_shipped',
    ];

    protected $casts = [
        'status' => ShippingStatusEnum::class,
        'cod_status' => ShippingCodStatusEnum::class,
        'metadata' => 'json',
        'estimate_date_shipped' => 'datetime',
        'date_shipped' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (Shipment $shipment) {
            ShipmentHistory::where('shipment_id', $shipment->id)->delete();
        });
    }

    public function store(): HasOne
    {
        return $this->hasOne(StoreLocator::class, 'id', 'store_id')->withDefault();
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ShipmentHistory::class, 'shipment_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class)->withDefault();
    }
}
