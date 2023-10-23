<?php

namespace Cmat\LanguageAdvanced\Providers;

use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\LanguageAdvanced\Listeners\AddDefaultTranslations;
use Cmat\LanguageAdvanced\Listeners\PriorityLanguageAdvancedPluginListener;
use Cmat\LanguageAdvanced\Listeners\ClearCacheAfterUpdateData;
use Cmat\PluginManagement\Events\ActivatedPluginEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CreatedContentEvent::class => [
            AddDefaultTranslations::class,
        ],
        UpdatedContentEvent::class => [
            ClearCacheAfterUpdateData::class,
        ],
        ActivatedPluginEvent::class => [
            PriorityLanguageAdvancedPluginListener::class,
        ],
    ];
}
