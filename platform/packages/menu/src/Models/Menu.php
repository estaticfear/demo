<?php

namespace Cmat\Menu\Models;

use Cmat\Base\Casts\SafeContent;
use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends BaseModel
{
    protected $table = 'menus';

    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Menu $menu) {
            MenuNode::where('menu_id', $menu->getKey())->delete();
        });
    }

    public function menuNodes(): HasMany
    {
        return $this->hasMany(MenuNode::class, 'menu_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(MenuLocation::class, 'menu_id');
    }
}
