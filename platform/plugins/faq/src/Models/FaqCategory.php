<?php

namespace Cmat\Faq\Models;

use Cmat\Base\Casts\SafeContent;
use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FaqCategory extends BaseModel
{
    protected $table = 'faq_categories';

    protected $fillable = [
        'name',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
    ];

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class, 'category_id');
    }
}
