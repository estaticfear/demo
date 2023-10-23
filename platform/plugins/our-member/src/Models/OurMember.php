<?php

namespace Cmat\OurMember\Models;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Models\BaseModel;

class OurMember extends BaseModel
{
    protected $table = 'our_members';

    protected $fillable = [
        'name',
        'status',
        'jobtitle',
        'introduct',
        'avatar',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}
