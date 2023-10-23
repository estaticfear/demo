<?php

namespace Cmat\ReligiousMerit\Models;

use Cmat\Member\Models\Member;
use Cmat\ReligiousMerit\Enums\PaymentGateTypeEnum;
use Cmat\ReligiousMerit\Enums\ReligiousMeritStatusEnum;
use Cmat\ReligiousMerit\Enums\ReligiousTypeEnum;
use Cmat\Base\Models\BaseModel;
use Cmat\Ecommerce\Enums\ProductTypeEnum;
use Cmat\Media\Models\MediaFile;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use RvMedia;

class ReligiousMerit extends BaseModel
{
    protected $table = 'religious_merits';

    protected $appends = ['transaction_image_url'];

    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'address',
        'is_hidden',
        'type',
        'amount',
        'man_day',
        'artifact',
        'artifacts',
        'project_id',
        'member_id',
        'payment_gate',
        'status',
        'transaction_image_id',
        'transaction_message'
        // 'transaction_image_url'
    ];

    protected $casts = [
        // Todo: resolve Casting error
        'type' => ReligiousTypeEnum::class,
        'payment_gate' => PaymentGateTypeEnum::class,
        'status' => ReligiousMeritStatusEnum::class,
    ];

    public function meritProducts(String $type = 'physical'): HasMany
    {
        return $this->hasMany(ReligiousMeritProduct::class, 'merit_id')->where('product_type', $type)->with(['meritProjectProduct']);
    }


    public function project(): BelongsTo
    {
        return  $this->belongsTo(ReligiousMeritProject::class, 'project_id');
    }

    public function member(): BelongsTo
    {
        return  $this->belongsTo(Member::class, 'member_id');
    }

    public function transactionImage(): BelongsTo
    {
        return $this->belongsTo(MediaFile::class, 'transaction_image_id')->withDefault();
    }

    public function getTransactionImageUrlAttribute()
    {
        return $this->transactionImage->url ? RvMedia::url($this->transactionImage->url) : null;
    }

    public function updateTotalMeritQty($meritProducts, $isIncrease, $isUpdate = false) {
        $cases = [];
        $ids = [];
        $params = [];
        foreach ($meritProducts as $mp) {
            $total_merit_qty = $mp->meritProjectProduct->total_merit_qty;
            if ($isUpdate) {
                $total_merit_qty = $mp->qty;
            } else {
                if ($isIncrease) $total_merit_qty += $mp->qty;
                else $total_merit_qty -= $mp->qty;
            }

            $id = (int) $mp->merit_project_product_id;
            $cases[] = "WHEN {$id} then ?";
            $params[] = $total_merit_qty;
            $ids[] = $id;
        }
        $ids = implode(',', $ids);
        $cases = implode(' ', $cases);
        if ($ids) {
            return DB::update("UPDATE `religious_merit_project_product` SET `total_merit_qty` = CASE `id` {$cases} END
                WHERE `id` in ({$ids})", $params);
        }
    }

    public static function boot()
    {
        parent::boot();

        static::updating(function ($merit) {
            $oldStatus = $merit->getOriginal('status');
            $status = $merit->status;
            $type = $merit->type;
            $product_type = $merit->type == ReligiousTypeEnum::ARTIFACT ? ProductTypeEnum::PHYSICAL : ProductTypeEnum::DIGITAL;
            $meritProducts = $merit->meritProducts($product_type)->get();
            if ($merit->isDirty('status')) {
                if (in_array($type, [ReligiousTypeEnum::ARTIFACT, ReligiousTypeEnum::EFFORT])) {
                    if (
                        in_array($oldStatus, [ReligiousMeritStatusEnum::IS_BOOKED, ReligiousMeritStatusEnum::SUCCESS])
                        && !in_array($status, [ReligiousMeritStatusEnum::IS_BOOKED, ReligiousMeritStatusEnum::SUCCESS])
                    ) {
                        // Giảm total_merit_qty cho product trong project
                        $merit->updateTotalMeritQty($meritProducts, false);
                    }

                    if (
                        !in_array($oldStatus, [ReligiousMeritStatusEnum::IS_BOOKED, ReligiousMeritStatusEnum::SUCCESS])
                        && in_array($status, [ReligiousMeritStatusEnum::IS_BOOKED, ReligiousMeritStatusEnum::SUCCESS])
                    ) {
                        // Tăng total_merit_qty cho product trong project
                        $merit->updateTotalMeritQty($meritProducts, true);
                    }
                }
            }
        });
    }
}
