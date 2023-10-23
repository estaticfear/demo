<?php

namespace Cmat\Contact;

use Cmat\PluginManagement\Abstracts\PluginOperationAbstract;
use Cmat\Setting\Models\Setting;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('contact_replies');
        Schema::dropIfExists('contacts');

        Setting::query()
            ->whereIn('key', [
                'blacklist_keywords',
                'blacklist_email_domains',
                'enable_math_captcha_for_contact_form',
            ])
            ->delete();
    }
}
