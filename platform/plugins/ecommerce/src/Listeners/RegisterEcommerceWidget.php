<?php

namespace Cmat\Ecommerce\Listeners;

use Cmat\Base\Events\RenderingAdminWidgetEvent;
use Cmat\Ecommerce\Widgets\CustomerChart;
use Cmat\Ecommerce\Widgets\NewCustomerCard;
use Cmat\Ecommerce\Widgets\NewOrderCard;
use Cmat\Ecommerce\Widgets\OrderChart;
use Cmat\Ecommerce\Widgets\NewProductCard;
use Cmat\Ecommerce\Widgets\RecentOrdersTable;
use Cmat\Ecommerce\Widgets\ReportGeneralHtml;
use Cmat\Ecommerce\Widgets\RevenueCard;
use Cmat\Ecommerce\Widgets\TopSellingProductsTable;
use Cmat\Ecommerce\Widgets\TrendingProductsTable;

class RegisterEcommerceWidget
{
    public function handle(RenderingAdminWidgetEvent $event): void
    {
        $event->widget
            ->register([
                RevenueCard::class,
                NewProductCard::class,
                NewCustomerCard::class,
                NewOrderCard::class,
                CustomerChart::class,
                OrderChart::class,
                ReportGeneralHtml::class,
                RecentOrdersTable::class,
                TopSellingProductsTable::class,
                TrendingProductsTable::class,
            ], 'ecommerce');
    }
}
