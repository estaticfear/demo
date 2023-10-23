<?php

namespace Cmat\Block\Models;

use Cmat\Base\Casts\SafeContent;
use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Models\BaseModel;

class Block extends BaseModel
{
    protected $table = 'blocks';

    protected $fillable = [
        'name',
        'description',
        'content',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'content' => SafeContent::class,
        'description' => SafeContent::class,
    ];
}
