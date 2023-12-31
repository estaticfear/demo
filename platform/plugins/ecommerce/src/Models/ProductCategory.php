<?php

namespace Cmat\Ecommerce\Models;

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Models\BaseModel;
use Cmat\Ecommerce\Tables\ProductTable;
use Html;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ProductCategory extends BaseModel
{
    protected $table = 'ec_product_categories';

    protected $fillable = [
        'name',
        'parent_id',
        'description',
        'order',
        'status',
        'image',
        'is_featured',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Product::class,
                'ec_product_category_product',
                'category_id',
                'product_id'
            )
            ->where('is_variation', 0);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id')->withDefault();
    }

    protected function parents(): Attribute
    {
        return Attribute::make(
            get: function (): Collection {
                $parents = collect();

                $parent = $this->parent;

                while ($parent->id) {
                    $parents->push($parent);
                    $parent = $parent->parent;
                }

                return $parents;
            },
        );
    }

    protected function badgeWithCount(): Attribute
    {
        return Attribute::make(
            get: function (): HtmlString {
                $badge = match ($this->status->getValue()) {
                    BaseStatusEnum::DRAFT => 'bg-secondary',
                    BaseStatusEnum::PENDING => 'bg-warning',
                    default => 'bg-success',
                };

                $link = route('products.index', [
                    'filter_table_id' => strtolower(Str::slug(Str::snake(ProductTable::class))),
                    'class' => Product::class,
                    'filter_columns' => ['category'],
                    'filter_operators' => ['='],
                    'filter_values' => [$this->id],
                ]);

                return Html::link($link, (string)$this->products_count, [
                    'class' => 'badge font-weight-bold ' . $badge,
                    'data-bs-toggle' => 'tooltip',
                    'data-bs-original-title' => trans('plugins/ecommerce::product-categories.total_products', ['total' => $this->products_count]),
                ]);
            },
        );
    }

    public function children(): HasMany
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    public function activeChildren(): HasMany
    {
        return $this
            ->children()
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->with(['slugable', 'activeChildren']);
    }

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (ProductCategory $category) {
            $category->products()->detach();

            foreach ($category->children()->get() as $child) {
                $child->delete();
            }
        });
    }
}
