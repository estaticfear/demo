<?php

namespace Cmat\Chart\Providers;

use Cmat\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Support\ServiceProvider;

class ChartServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this->setNamespace('core/chart')
            ->loadAndPublishViews();
    }
}
