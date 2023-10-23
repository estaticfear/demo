<?php

namespace Cmat\AuditLog;

use Cmat\PluginManagement\Abstracts\PluginOperationAbstract;
use Cmat\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('audit_histories');
        app(DashboardWidgetInterface::class)->deleteBy(['name' => 'widget_audit_logs']);
    }
}
