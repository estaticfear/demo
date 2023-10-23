<?php

namespace Cmat\Captcha;

use Cmat\PluginManagement\Abstracts\PluginOperationAbstract;
use Cmat\Setting\Models\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Setting::query()
            ->whereIn('key', [
                'enable_captcha',
                'captcha_type',
                'captcha_hide_badge',
                'captcha_site_key',
                'captcha_secret',
            ])
            ->delete();
    }
}
