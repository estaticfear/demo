<?php

namespace Cmat\Ecommerce\Models;

use Cmat\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Option extends BaseModel
{
    protected $table = 'ec_options';

    protected $fillable = [
        'name',
        'option_type',
        'required',
        'product_id',
        'order',
    ];

    public function values(): HasMany
    {
        return $this
            ->hasMany(OptionValue::class, 'option_id')
            ->orderBy('order', 'ASC');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (Option $option) {
            OptionValue::where('option_id', $option->id)->delete();
        });
    }
}
