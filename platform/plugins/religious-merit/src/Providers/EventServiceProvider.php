<?php

namespace Cmat\ReligiousMerit\Providers;

use Cmat\ReligiousMerit\Events\UpdatedMeritFromOrToSuccessEvent;
use Cmat\ReligiousMerit\Listeners\UpdatedMeritFromOrToSuccessListener;
use Cmat\ReligiousMerit\Listeners\VnpayTransactionUpdatedListener;
use Cmat\Vnpay\Events\VnpayTransactionUpdatedEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        VnpayTransactionUpdatedEvent::class => [
            VnpayTransactionUpdatedListener::class
        ],
        UpdatedMeritFromOrToSuccessEvent::class => [
            UpdatedMeritFromOrToSuccessListener::class
        ]
    ];
}
