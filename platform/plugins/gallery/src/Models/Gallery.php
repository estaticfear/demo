<?php

namespace Cmat\Gallery\Models;

use Cmat\ACL\Models\User;
use Cmat\Base\Casts\SafeContent;
use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends BaseModel
{
    protected $table = 'galleries';

    protected $fillable = [
        'name',
        'description',
        'is_featured',
        'order',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'description' => SafeContent::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
