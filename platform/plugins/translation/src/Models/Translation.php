<?php

namespace Cmat\Translation\Models;

use Cmat\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Translation extends BaseModel
{
    public const STATUS_SAVED = 0;
    public const STATUS_CHANGED = 1;

    protected $table = 'translations';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function scopeOfTranslatedGroup(Builder $query, $group)
    {
        $query->where('group', $group)->whereNotNull('value');
    }

    public function scopeOrderByGroupKeys(Builder $query, bool $ordered)
    {
        if ($ordered) {
            $query->orderBy('group')->orderBy('key');
        }
    }

    public function scopeSelectDistinctGroup(Builder $query)
    {
        $select = match (config('database.default')) {
            'mysql' => 'DISTINCT `group`',
            default => 'DISTINCT "group"',
        };

        $query->select(DB::raw($select));
    }
}
