<?php

namespace Cmat\Ecommerce\Models;

use Cmat\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GlobalOption extends BaseModel
{
    protected $table = 'ec_global_options';

    protected $fillable = [
        'name',
        'option_type',
        'required',
    ];

    public function values(): HasMany
    {
        return $this
            ->hasMany(GlobalOptionValue::class, 'option_id')
            ->orderBy('order', 'ASC');
    }

    protected function optionName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name
        );
    }

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (GlobalOption $option) {
            GlobalOptionValue::where('option_id', $option->id)->delete();
        });
    }
}
