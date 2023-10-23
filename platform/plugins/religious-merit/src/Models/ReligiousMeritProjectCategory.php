<?php

namespace Cmat\ReligiousMerit\Models;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReligiousMeritProjectCategory extends BaseModel
{
    protected $table = 'religious_merit_project_categories';

    protected $fillable = [
        'name',
        'description',
        'content',
        'image',
        'author_id',
        'status',
        'order'
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(ReligiousMeritProject::class, 'project_category_id');
    }
}
