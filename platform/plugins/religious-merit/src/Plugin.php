<?php

namespace Cmat\ReligiousMerit;

use Illuminate\Support\Facades\Schema;
use Cmat\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('religious_merits');
        Schema::dropIfExists('religious_merits_translations');
    }
}
