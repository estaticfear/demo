<?php

namespace Cmat\Setting\Models;

use Cmat\Base\Models\BaseModel;

class Setting extends BaseModel
{
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
    ];
}
