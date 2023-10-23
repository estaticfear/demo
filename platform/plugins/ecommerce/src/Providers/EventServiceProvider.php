<?php

namespace Cmat\Ecommerce\Providers;

use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\RenderingAdminWidgetEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Ecommerce\Events\OrderCancelledEvent;
use Cmat\Ecommerce\Events\OrderCompletedEvent;
use Cmat\Ecommerce\Events\OrderCreated;
use Cmat\Ecommerce\Events\OrderPaymentConfirmedEvent;
use Cmat\Ecommerce\Events\OrderPlacedEvent;
use Cmat\Ecommerce\Events\OrderReturnedEvent;
use Cmat\Ecommerce\Events\ProductQuantityUpdatedEvent;
use Cmat\Ecommerce\Events\ProductViewed;
use Cmat\Ecommerce\Events\ShippingStatusChanged;
use Cmat\Ecommerce\Listeners\AddLanguageForVariantsListener;
use Cmat\Ecommerce\Listeners\GenerateInvoiceListener;
use Cmat\Ecommerce\Listeners\OrderCancelledNotification;
use Cmat\Ecommerce\Listeners\OrderCreatedNotification;
use Cmat\Ecommerce\Listeners\OrderPaymentConfirmedNotification;
use Cmat\Ecommerce\Listeners\OrderReturnedNotification;
use Cmat\Ecommerce\Listeners\RegisterCodPaymentMethod;
use Cmat\Ecommerce\Listeners\RegisterEcommerceWidget;
use Cmat\Ecommerce\Listeners\RenderingSiteMapListener;
use Cmat\Ecommerce\Listeners\SendMailsAfterCustomerRegistered;
use Cmat\Ecommerce\Listeners\SendProductReviewsMailAfterOrderCompleted;
use Cmat\Ecommerce\Listeners\SendShippingStatusChangedNotification;
use Cmat\Ecommerce\Listeners\SendWebhookWhenOrderPlaced;
use Cmat\Ecommerce\Listeners\UpdateProductStockStatus;
use Cmat\Ecommerce\Listeners\UpdateProductView;
use Cmat\Payment\Events\RenderingPaymentMethods;
use Cmat\Theme\Events\RenderingSiteMapEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RenderingSiteMapEvent::class => [
            RenderingSiteMapListener::class,
        ],
        CreatedContentEvent::class => [
            AddLanguageForVariantsListener::class,
        ],
        UpdatedContentEvent::class => [
            AddLanguageForVariantsListener::class,
        ],
        Registered::class => [
            SendMailsAfterCustomerRegistered::class,
        ],
        OrderPlacedEvent::class => [
            SendWebhookWhenOrderPlaced::class,
            GenerateInvoiceListener::class,
            OrderCreatedNotification::class,
        ],
        OrderCreated::class => [
            GenerateInvoiceListener::class,
            OrderCreatedNotification::class,
        ],
        ProductQuantityUpdatedEvent::class => [
            UpdateProductStockStatus::class,
        ],
        OrderCompletedEvent::class => [
            SendProductReviewsMailAfterOrderCompleted::class,
        ],
        ProductViewed::class => [
            UpdateProductView::class,
        ],
        ShippingStatusChanged::class => [
            SendShippingStatusChangedNotification::class,
        ],
        RenderingAdminWidgetEvent::class => [
            RegisterEcommerceWidget::class,
        ],
        OrderPaymentConfirmedEvent::class => [
            OrderPaymentConfirmedNotification::class,
        ],
        OrderCancelledEvent::class => [
            OrderCancelledNotification::class,
        ],
        OrderReturnedEvent::class => [
            OrderReturnedNotification::class,
        ],
        RenderingPaymentMethods::class => [
            RegisterCodPaymentMethod::class,
        ],
    ];
}
