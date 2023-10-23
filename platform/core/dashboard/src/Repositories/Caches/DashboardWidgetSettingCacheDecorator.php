<?php

namespace Cmat\Dashboard\Repositories\Caches;

use Cmat\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class DashboardWidgetSettingCacheDecorator extends CacheAbstractDecorator implements DashboardWidgetSettingInterface
{
    public function getListWidget()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
