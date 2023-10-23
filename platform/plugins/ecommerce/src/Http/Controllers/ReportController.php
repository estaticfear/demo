<?php

namespace Cmat\Ecommerce\Http\Controllers;

use Assets;
use Cmat\Base\Http\Controllers\BaseController;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Base\Widgets\Contracts\AdminWidget;
use Cmat\Ecommerce\Enums\OrderStatusEnum;
use Cmat\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Cmat\Ecommerce\Repositories\Interfaces\OrderInterface;
use Cmat\Ecommerce\Repositories\Interfaces\ProductInterface;
use Cmat\Ecommerce\Tables\Reports\RecentOrdersTable;
use Cmat\Ecommerce\Tables\Reports\TopSellingProductsTable;
use Cmat\Ecommerce\Tables\Reports\TrendingProductsTable;
use Carbon\Carbon;
use EcommerceHelper;
use Illuminate\Http\Request;

class ReportController extends BaseController
{
    public function __construct(
        protected OrderInterface $orderRepository,
        protected ProductInterface $productRepository,
        protected CustomerInterface $customerRepository
    ) {
    }

    public function getIndex(
        Request $request,
        AdminWidget $widget,
        BaseHttpResponse $response
    ) {
        page_title()->setTitle(trans('plugins/ecommerce::reports.name'));

        Assets::addScriptsDirectly([
            'vendor/core/plugins/ecommerce/libraries/daterangepicker/daterangepicker.js',
            'vendor/core/plugins/ecommerce/js/report.js',
        ])
            ->addStylesDirectly([
                'vendor/core/plugins/ecommerce/libraries/daterangepicker/daterangepicker.css',
                'vendor/core/plugins/ecommerce/css/report.css',
            ])
            ->usingVueJS();

        [$startDate, $endDate] = EcommerceHelper::getDateRangeInReport($request);

        if ($request->ajax()) {
            return $response->setData(view('plugins/ecommerce::reports.ajax', compact('widget'))->render());
        }

        return view(
            'plugins/ecommerce::reports.index',
            compact('startDate', 'endDate', 'widget')
        );
    }

    public function getTopSellingProducts(TopSellingProductsTable $topSellingProductsTable)
    {
        return $topSellingProductsTable->renderTable();
    }

    public function getRecentOrders(RecentOrdersTable $recentOrdersTable)
    {
        return $recentOrdersTable->renderTable();
    }

    public function getTrendingProducts(TrendingProductsTable $trendingProductsTable)
    {
        return $trendingProductsTable->renderTable();
    }

    public function getDashboardWidgetGeneral(BaseHttpResponse $response)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $today = Carbon::now();

        $processingOrders = $this->orderRepository
            ->getModel()
            ->where('status', OrderStatusEnum::PENDING)
            ->whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $today)
            ->count();

        $completedOrders = $this->orderRepository
            ->getModel()
            ->where('status', OrderStatusEnum::COMPLETED)
            ->whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $today)
            ->count();

        $revenue = $this->orderRepository->countRevenueByDateRange($startOfMonth, $today);

        $lowStockProducts = $this->productRepository
            ->getModel()
            ->where('with_storehouse_management', 1)
            ->where('quantity', '<', 2)
            ->where('quantity', '>', 0)
            ->count();

        $outOfStockProducts = $this->productRepository
            ->getModel()
            ->where('with_storehouse_management', 1)
            ->where('quantity', '<', 1)
            ->count();

        return $response
            ->setData(
                view(
                    'plugins/ecommerce::reports.widgets.general',
                    compact(
                        'processingOrders',
                        'revenue',
                        'completedOrders',
                        'outOfStockProducts',
                        'lowStockProducts'
                    )
                )->render()
            );
    }
}
