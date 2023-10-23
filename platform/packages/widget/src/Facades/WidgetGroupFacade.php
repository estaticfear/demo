<?php

namespace Cmat\Widget\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Widget\WidgetGroupCollection
 */
class WidgetGroupFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'cmat.widget-group-collection';
    }
}
