<?php

namespace Cmat\Ecommerce\Models;

use Cmat\Base\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

class ProductView extends BaseModel
{
    use MassPrunable;

    protected $table = 'ec_product_views';

    protected $fillable = [
        'product_id',
        'views',
        'date',
    ];

    protected $casts = [
        'views' => 'int',
        'date' => 'date',
    ];

    public $timestamps = false;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function prunable(): Builder
    {
        return $this->whereDate('created_at', '>', Carbon::now()->subDays(90)->toDateString());
    }
}
