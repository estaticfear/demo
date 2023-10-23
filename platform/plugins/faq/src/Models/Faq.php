<?php

namespace Cmat\Faq\Models;

use Cmat\Base\Casts\SafeContent;
use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faq extends BaseModel
{
    protected $table = 'faqs';

    protected $fillable = [
        'question',
        'answer',
        'category_id',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'question' => SafeContent::class,
        'answer' => SafeContent::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FaqCategory::class, 'category_id')->withDefault();
    }
}
