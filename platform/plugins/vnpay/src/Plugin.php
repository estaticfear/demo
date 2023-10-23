<?php

namespace Cmat\Vnpay;

use Illuminate\Support\Facades\Schema;
use Cmat\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('vnpays');
        Schema::dropIfExists('vnpays_translations');
    }
}
