<?php

namespace Cmat\CustomField;

use Cmat\PluginManagement\Abstracts\PluginOperationAbstract;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('custom_fields');
        Schema::dropIfExists('field_items');
        Schema::dropIfExists('field_groups');
        Schema::dropIfExists('custom_fields_translations');
    }
}
