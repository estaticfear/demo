<?php

namespace Cmat\WidgetGenerator\Providers;

use Illuminate\Support\ServiceProvider;

class WidgetGeneratorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->register(CommandServiceProvider::class);
    }
}
