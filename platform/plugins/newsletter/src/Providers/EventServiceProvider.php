<?php

namespace Cmat\Newsletter\Providers;

use Cmat\Newsletter\Events\SubscribeNewsletterEvent;
use Cmat\Newsletter\Events\UnsubscribeNewsletterEvent;
use Cmat\Newsletter\Listeners\AddSubscriberToMailchimpContactListListener;
use Cmat\Newsletter\Listeners\AddSubscriberToSendGridContactListListener;
use Cmat\Newsletter\Listeners\RemoveSubscriberToMailchimpContactListListener;
use Cmat\Newsletter\Listeners\SendEmailNotificationAboutNewSubscriberListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SubscribeNewsletterEvent::class => [
            SendEmailNotificationAboutNewSubscriberListener::class,
            AddSubscriberToMailchimpContactListListener::class,
            AddSubscriberToSendGridContactListListener::class,
        ],
        UnsubscribeNewsletterEvent::class => [
            RemoveSubscriberToMailchimpContactListListener::class,
        ],
    ];
}
