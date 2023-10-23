<?php

namespace Cmat\Language\Providers;

use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Language\Listeners\ActivatedPluginListener;
use Cmat\Language\Listeners\AddHrefLangListener;
use Cmat\Language\Listeners\CreatedContentListener;
use Cmat\Language\Listeners\DeletedContentListener;
use Cmat\Language\Listeners\ThemeRemoveListener;
use Cmat\Language\Listeners\UpdatedContentListener;
use Cmat\PluginManagement\Events\ActivatedPluginEvent;
use Cmat\Theme\Events\RenderingSingleEvent;
use Cmat\Theme\Events\ThemeRemoveEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UpdatedContentEvent::class => [
            UpdatedContentListener::class,
        ],
        CreatedContentEvent::class => [
            CreatedContentListener::class,
        ],
        DeletedContentEvent::class => [
            DeletedContentListener::class,
        ],
        ThemeRemoveEvent::class => [
            ThemeRemoveListener::class,
        ],
        ActivatedPluginEvent::class => [
            ActivatedPluginListener::class,
        ],
        RenderingSingleEvent::class => [
            AddHrefLangListener::class,
        ],
    ];
}
