<?php

namespace Cmat\LanguageAdvanced\Listeners;

use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Support\Services\Cache\Cache;

class ClearCacheAfterUpdateData
{
    public function handle(UpdatedContentEvent $event): void
    {
        if (setting('enable_cache', false)) {
            $cache = new Cache(app('cache'), get_class($event->data));
            $cache->flush();
        }
    }
}
