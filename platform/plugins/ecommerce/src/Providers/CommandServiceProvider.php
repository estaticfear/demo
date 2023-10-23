<?php

namespace Cmat\Ecommerce\Providers;

use Cmat\Ecommerce\Commands\BulkImportProductCommand;
use Cmat\Ecommerce\Commands\SendAbandonedCartsEmailCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            SendAbandonedCartsEmailCommand::class,
            BulkImportProductCommand::class,
        ]);
    }
}
