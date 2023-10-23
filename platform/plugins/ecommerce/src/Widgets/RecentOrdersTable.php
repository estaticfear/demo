<?php

namespace Cmat\Ecommerce\Widgets;

use Cmat\Base\Widgets\Table;
use Cmat\Ecommerce\Tables\Reports\RecentOrdersTable as BaseRecentOrdersTable;

class RecentOrdersTable extends Table
{
    protected string $table = BaseRecentOrdersTable::class;

    protected string $route = 'ecommerce.report.recent-orders';

    public function getLabel(): string
    {
        return trans('plugins/ecommerce::reports.recent_orders');
    }
}
