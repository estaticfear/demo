<?php

namespace Cmat\Ecommerce\Widgets;

use Cmat\Base\Widgets\Html;
use Cmat\Ecommerce\Repositories\Interfaces\OrderInterface;
use Carbon\CarbonPeriod;
use Cmat\Payment\Enums\PaymentStatusEnum;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ReportGeneralHtml extends Html
{
    public function getContent(): string
    {
        if (! is_plugin_active('payment')) {
            return '';
        }

        $count = [
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ];

        $revenues = app(OrderInterface::class)
            ->getModel()
            ->select([
                DB::raw('SUM(COALESCE(payments.amount, 0) - COALESCE(payments.refunded_amount, 0)) as revenue'),
                'payments.status',
            ])
            ->join('payments', 'payments.id', '=', 'ec_orders.payment_id')
            ->whereIn('payments.status', [PaymentStatusEnum::COMPLETED, PaymentStatusEnum::PENDING])
            ->whereDate('payments.created_at', '>=', $this->startDate)
            ->whereDate('payments.created_at', '<=', $this->endDate)
            ->groupBy('payments.status')
            ->get();

        $revenueCompleted = $revenues->firstWhere('status', PaymentStatusEnum::COMPLETED);
        $revenuePending = $revenues->firstWhere('status', PaymentStatusEnum::PENDING);

        $count['revenues'] = [
            [
                'label' => PaymentStatusEnum::COMPLETED()->label(),
                'value' => $revenueCompleted ? (int)$revenueCompleted->revenue : 0,
                'status' => true,
                'color' => '#80bc00',
            ],
            [
                'label' => PaymentStatusEnum::PENDING()->label(),
                'value' => $revenuePending ? (int)$revenuePending->revenue : 0,
                'status' => false,
                'color' => '#E91E63',
            ],
        ];

        $revenues = app(OrderInterface::class)->getRevenueData($this->startDate, $this->endDate);

        $series = [];
        $dates = [];
        $earningSales = collect();
        $period = CarbonPeriod::create($this->startDate->startOfDay(), $this->endDate->endOfDay());

        $colors = ['#fcb800', '#80bc00'];

        $data = [
            'name' => get_application_currency()->title,
            'data' => [],
        ];

        foreach ($period as $date) {
            $value = $revenues
                ->where('date', $date->format('Y-m-d'))
                ->sum('revenue');

            $data['data'][] = (float) $value;
        }

        $earningSales[] = [
            'text' => trans('plugins/ecommerce::reports.items_earning_sales', [
                'value' => format_price(collect($data['data'])->sum()),
            ]),
            'color' => Arr::get($colors, $earningSales->count(), Arr::first($colors)),
        ];
        $series[] = $data;

        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        $colors = $earningSales->pluck('color');

        $salesReport = compact('dates', 'series', 'earningSales', 'colors');

        return view('plugins/ecommerce::reports.widgets.revenues', compact('count', 'salesReport'))->render();
    }
}
