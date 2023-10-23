<?php

namespace Cmat\Block;

use Cmat\PluginManagement\Abstracts\PluginOperationAbstract;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('blocks');
        Schema::dropIfExists('blocks_translations');
    }
}
