<?php

namespace Cmat\OurMember;

use Illuminate\Support\Facades\Schema;
use Cmat\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('our_members');
        Schema::dropIfExists('our_members_translations');
    }
}
