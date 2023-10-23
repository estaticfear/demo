<?php

namespace Cmat\Gallery\Models;

use Cmat\Base\Models\BaseModel;

class GalleryMeta extends BaseModel
{
    protected $table = 'gallery_meta';

    protected $casts = [
        'images' => 'json',
    ];
}
