<?php

namespace Cmat\Ecommerce\Models;

use Cmat\Base\Models\BaseModel;
use Cmat\Ecommerce\Traits\LocationTrait;
use EcommerceHelper;

class Address extends BaseModel
{
    use LocationTrait;

    protected $table = 'ec_customer_addresses';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'country',
        'state',
        'city',
        'address',
        'zip_code',
        'customer_id',
        'is_default',
    ];

    public function getFullAddressAttribute(): string
    {
        return ($this->address ? ($this->address . ', ') : null) .
            ($this->city_name ? ($this->city_name . ', ') : null) .
            ($this->state_name ? ($this->state_name . ', ') : null) .
            (EcommerceHelper::isUsingInMultipleCountries() ? ($this->country_name ?: null) : '') .
            (EcommerceHelper::isZipCodeEnabled() && $this->zip_code ? ', ' . $this->zip_code : '');
    }
}
