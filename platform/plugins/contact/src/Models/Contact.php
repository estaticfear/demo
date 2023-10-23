<?php

namespace Cmat\Contact\Models;

use Cmat\Base\Supports\Avatar;
use Cmat\Contact\Enums\ContactStatusEnum;
use Cmat\Base\Models\BaseModel;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RvMedia;

class Contact extends BaseModel
{
    protected $table = 'contacts';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'subject',
        'content',
        'status',
    ];

    protected $casts = [
        'status' => ContactStatusEnum::class,
    ];

    public function replies(): HasMany
    {
        return $this->hasMany(ContactReply::class);
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                try {
                    return (new Avatar())->create($this->name)->toBase64();
                } catch (Exception) {
                    return RvMedia::getDefaultImage();
                }
            },
        );
    }
}
