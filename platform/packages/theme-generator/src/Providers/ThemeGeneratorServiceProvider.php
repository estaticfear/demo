<?php

namespace Cmat\ThemeGenerator\Providers;

use Illuminate\Support\ServiceProvider;

class ThemeGeneratorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->register(CommandServiceProvider::class);
    }
}
