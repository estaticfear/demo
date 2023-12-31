<?php

namespace Cmat\Ecommerce\Models;

use Cmat\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class ProductFile extends BaseModel
{
    protected $table = 'ec_product_files';

    protected $fillable = [
        'product_id',
        'url',
        'extras',
    ];

    protected $casts = [
        'extras' => 'json',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withDefault();
    }

    public function getFileNameAttribute(): string
    {
        return Arr::get($this->extras, 'name', '');
    }

    public function getFileSizeAttribute(): int
    {
        return Arr::get($this->extras, 'size', 0);
    }

    public function getMimeTypeAttribute(): string
    {
        return Arr::get($this->extras, 'mime_type', '');
    }

    public function getFileExtensionAttribute(): string
    {
        return Arr::get($this->extras, 'extension', '');
    }

    public function getBasenameAttribute(): string
    {
        return $this->file_name . '.' . $this->file_extension;
    }
}
