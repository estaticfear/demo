<?php

namespace Cmat\Ecommerce\Models;

use Cmat\Base\Models\BaseModel;
use Cmat\Base\Supports\Avatar;
use Cmat\Ecommerce\Enums\CustomerStatusEnum;
use Cmat\Ecommerce\Notifications\ConfirmEmailNotification;
use Cmat\Ecommerce\Notifications\ResetPasswordNotification;
use Cmat\Marketplace\Models\Revenue;
use Cmat\Marketplace\Models\VendorInfo;
use Cmat\Marketplace\Models\Withdrawal;
use Cmat\Payment\Models\Payment;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use RvMedia;
use MacroableModels;
use Illuminate\Support\Str;

class Customer extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use MustVerifyEmail;
    use HasApiTokens;
    use Notifiable;

    protected $table = 'ec_customers';

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'dob',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status' => CustomerStatusEnum::class,
    ];

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new ConfirmEmailNotification());
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->avatar) {
                    return RvMedia::getImageUrl($this->avatar, 'thumb');
                }

                try {
                    return (new Avatar())->create($this->name)->toBase64();
                } catch (Exception) {
                    return RvMedia::getDefaultImage();
                }
            }
        );
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function addresses(): HasMany
    {
        $with = [];
        if (is_plugin_active('location')) {
            $with = ['locationCountry', 'locationState', 'locationCity'];
        }

        return $this->hasMany(Address::class, 'customer_id', 'id')->with($with);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'customer_id', 'id');
    }

    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'ec_discount_customers', 'customer_id', 'id');
    }

    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'customer_id');
    }

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (Customer $customer) {
            $customer->discounts()->detach();
            $customer->usedCoupons()->detach();
            Review::where('customer_id', $customer->id)->delete();
            Wishlist::where('customer_id', $customer->id)->delete();
            Address::where('customer_id', $customer->id)->delete();
            Order::where('user_id', $customer->id)->update(['user_id' => 0]);

            if (is_plugin_active('marketplace')) {
                Revenue::where('customer_id', $customer->id)->delete();
                Withdrawal::where('customer_id', $customer->id)->delete();
                VendorInfo::where('customer_id', $customer->id)->delete();
            }
        });

        static::deleted(function (Customer $customer) {
            $folder = Storage::path($customer->upload_folder);
            if (File::isDirectory($folder) && Str::endsWith($customer->upload_folder, '/' . $customer->id)) {
                File::deleteDirectory($folder);
            }
        });
    }

    public function __get($key)
    {
        if (class_exists('MacroableModels')) {
            $method = 'get' . Str::studly($key) . 'Attribute';
            if (MacroableModels::modelHasMacro(get_class($this), $method)) {
                return call_user_func([$this, $method]);
            }
        }

        return parent::__get($key);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    public function promotions(): BelongsToMany
    {
        return $this
            ->belongsToMany(Discount::class, 'ec_discount_customers', 'customer_id')
            ->where('type', 'promotion')
            ->where('start_date', '<=', Carbon::now())
            ->where('target', 'customer')
            ->where(function ($query) {
                return $query
                    ->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::now());
            })
            ->where('product_quantity', 1);
    }

    public function viewedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'ec_customer_recently_viewed_products');
    }

    public function usedCoupons(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'ec_customer_used_coupons');
    }

    protected function uploadFolder(): Attribute
    {
        return Attribute::make(
            get: function () {
                $folder = $this->id ? 'customers/' . $this->id : 'customers';

                return apply_filters('ecommerce_customer_upload_folder', $folder, $this);
            }
        );
    }
}
