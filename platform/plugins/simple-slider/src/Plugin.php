<?php

namespace Cmat\SimpleSlider;

use Cmat\Setting\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Cmat\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('simple_sliders');
        Schema::dropIfExists('simple_slider_items');

        Setting::query()
            ->whereIn('key', [
                'simple_slider_using_assets',
            ])
            ->delete();
    }
}
