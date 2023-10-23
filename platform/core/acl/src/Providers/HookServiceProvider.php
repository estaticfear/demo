<?php

namespace Cmat\ACL\Providers;

use Cmat\ACL\Hooks\UserWidgetHook;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_filter(DASHBOARD_FILTER_ADMIN_LIST, [UserWidgetHook::class, 'addUserStatsWidget'], 12, 2);
    }
}
