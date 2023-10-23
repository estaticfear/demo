<?php

namespace Cmat\Newsletter\Models;

use Cmat\Base\Casts\SafeContent;
use Cmat\Base\Models\BaseModel;
use Cmat\Newsletter\Enums\NewsletterStatusEnum;

class Newsletter extends BaseModel
{
    protected $table = 'newsletters';

    protected $fillable = [
        'email',
        'name',
        'status',
    ];

    protected $casts = [
        'name' => SafeContent::class,
        'status' => NewsletterStatusEnum::class,
    ];
}
