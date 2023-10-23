<?php

namespace Cmat\ReligiousMerit\Models;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Models\BaseModel;
use Cmat\ReligiousMerit\Models\ReligiousMeritProjectProduct;
use Cmat\ReligiousMerit\Enums\ReligiousMeritStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Cmat\ReligiousMerit\Enums\ReligiousTypeEnum;

class ReligiousMeritProject extends BaseModel
{
    protected $table = 'religious_merit_projects';

    // current_amount: Tổng tiền đã nhận được (bao gồm tiền mặt, hiện vật, công sức)
    // money_current_amount: Tổng tiền mặt đã nhận được
    // digital_products_current_amount: Tổng tiền mặt đã nhận được quy đổi từ Công Sức
    // physical_products_current_amount: Tổng tiền mặt đã nhận được quy đổi từ Hiện Vật
    // total_expected_amount: Tổng tiền mong muốn nhận đóng góp (quy đổi từ Tiền, Hiện Vật, Công Sức)
    protected $appends = ['is_finished', 'cost_estimations_data', 'physical_products', 'digital_products',
        'total_merits', 'money_current_amount', 'total_expected_amount', 'current_percent_merit',
        'digital_products_expectation_amount', 'digital_products_current_amount',
        'physical_products_expectation_amount', 'physical_products_current_amount'
    ];

    protected $fillable = ['name', 'description', 'content', 'image', 'start_date', 'to_date',
        'expectation_amount', 'current_amount',
        'can_contribute_effort', 'contribute_effort', 'can_contribute_artifact', 'contribute_artifact', 'order', 'author_id', 'author_type',
        'status', 'project_category_id', 'is_featured', 'cost_estimations', 'transaction_message_prefix'
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'cost_estimations' => 'json',
    ];

    public static function boot()
    {
        parent::boot();

        self::saving(function($model){
            if ($model->cost_estimations == '[]') $model->cost_estimations = [];
            else {
                $cost_estimations = [];
                foreach ($model->cost_estimations as $cost) {
                    $isHasData = false;
                    foreach ($cost as $c) {
                        if (!empty($c['value'])) $isHasData = true;
                    }
                    if ($isHasData) array_push($cost_estimations, $cost);
                }
                $model->cost_estimations = $cost_estimations;
            }

            if ($model->can_contribute_effort) {
                $model->contribute_effort = $model->getDigitalProductsExpectationAmountAttribute();
            }
            if ($model->can_contribute_artifact) {
                $model->contribute_artifact = $model->getPhysicalProductsExpectationAmountAttribute();
            }
        });
    }

    public function merits(): HasMany
    {
        return $this->hasMany(ReligiousMerit::class)->where('status', ReligiousMeritStatusEnum::SUCCESS);
    }

    public function products(String $type = 'physical'): HasMany
    {
        return $this->hasMany(ReligiousMeritProjectProduct::class, 'merit_project_id')->where('product_type', $type)->with(['product']);
    }

    public function getPhysicalProductsAttribute() {
        return $this->products('physical')->get();
    }

    public function getPhysicalProductsExpectationAmountAttribute() {
        return $this->products('physical')->sum(\DB::raw('qty * price'));
    }

    public function getPhysicalProductsCurrentAmountAttribute() {
        return ReligiousMerit::where('project_id', $this->id)
            ->whereIn('status', [ReligiousMeritStatusEnum::SUCCESS, ReligiousMeritStatusEnum::IS_BOOKED])
            ->where('type', ReligiousTypeEnum::ARTIFACT)
            ->sum('amount');
    }

    public function getDigitalProductsAttribute() {
        return $this->products('digital')->get();
    }

    public function getDigitalProductsExpectationAmountAttribute() {
        return $this->products('digital')->sum(\DB::raw('qty * price'));
    }

    public function getDigitalProductsCurrentAmountAttribute() {
        return ReligiousMerit::where('project_id', $this->id)
            ->whereIn('status', [ReligiousMeritStatusEnum::SUCCESS, ReligiousMeritStatusEnum::IS_BOOKED])
            ->where('type', ReligiousTypeEnum::EFFORT)
            ->sum('amount');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ReligiousMeritProjectCategory::class, 'project_category_id');
    }

    public function getTotalMerits()
    {
        return ReligiousMerit::where('project_id', $this->id)
            ->whereIn('status', [ReligiousMeritStatusEnum::SUCCESS, ReligiousMeritStatusEnum::IS_BOOKED])
            ->count();
    }

    public function getTotalMeritsAttribute()
    {
        return $this->getTotalMerits();
    }

    public function getMoneyCurrentAmountAttribute()
    {
        return ReligiousMerit::where('project_id', $this->id)
            ->where('status', ReligiousMeritStatusEnum::SUCCESS)
            ->where('type', ReligiousTypeEnum::MONEY)
            ->sum('amount');
    }

    public function getTotalExpectedAmountAttribute()
    {
        $total = $this->expectation_amount;
        if ($this->can_contribute_effort) $total += $this->contribute_effort;
        if ($this->can_contribute_artifact) $total += $this->contribute_artifact;

        return $total;
    }

    public function getCurrentPercentMeritAttribute() {
        $currentAmount = $this->current_amount;
        $totalExpectedAmount = $this->getTotalExpectedAmountAttribute();
        if ($totalExpectedAmount) return $currentAmount / $totalExpectedAmount * 100;
        return 0;
    }

    public function getIsFinishedAttribute()
    {
        $currentDate = \Carbon\Carbon::now();
        return $this->status == BaseStatusEnum::PUBLISHED && ($this->current_amount >= $this->expectation_amount || $this->to_date < $currentDate);
    }

    public function getCostEstimationsDataAttribute()
    {
        $costEstimations = $this->cost_estimations;
        if ($costEstimations == '[]') $costEstimations = [];
        return $costEstimations;
    }
}
