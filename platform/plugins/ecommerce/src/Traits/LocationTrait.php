<?php

namespace Cmat\Ecommerce\Traits;

use Cmat\Location\Models\City;
use Cmat\Location\Models\Country;
use Cmat\Location\Models\State;
use EcommerceHelper;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Eloquent
 */
trait LocationTrait
{
    public function getCountryNameAttribute(): ?string
    {
        $value = $this->country;

        if (! $value || ! is_plugin_active('location')) {
            return $value;
        }

        if (is_numeric($value)) {
            $countryName = $this->locationCountry->name;

            if ($countryName) {
                return $countryName;
            }
        }

        return $value;
    }

    public function locationCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country')->withDefault();
    }

    public function locationState(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state')->withDefault();
    }

    public function locationCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city')->withDefault();
    }

    public function getStateNameAttribute(): ?string
    {
        $value = $this->state;

        if (! $value || ! is_plugin_active('location')) {
            return $value;
        }

        if (is_numeric($value)) {
            $stateName = $this->locationState->name;

            if ($stateName) {
                return $stateName;
            }
        }

        return $value;
    }

    public function getCityNameAttribute(): ?string
    {
        $value = $this->city;

        if (! $value || ! is_plugin_active('location')) {
            return $value;
        }

        if (is_numeric($value)) {
            $cityName = $this->locationCity->name;

            if ($cityName) {
                return $cityName;
            }
        }

        return $value;
    }

    public function getFullAddressAttribute(): string
    {
        return ($this->address ? ($this->address . ', ') : null) .
            ($this->city_name ? ($this->city_name . ', ') : null) .
            ($this->state_name ? ($this->state_name . ', ') : null) .
            ($this->country_name ?: null) .
            (EcommerceHelper::isZipCodeEnabled() && $this->zip_code ? ', ' . $this->zip_code : '');
    }
}
