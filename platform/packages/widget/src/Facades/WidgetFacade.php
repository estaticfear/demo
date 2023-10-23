<?php

namespace Cmat\Widget\Facades;

use Cmat\Widget\WidgetGroup;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Widget\Factories\WidgetFactory
 */
class WidgetFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'cmat.widget';
    }

    public static function group(string $name): WidgetGroup
    {
        return app('cmat.widget-group-collection')->group($name);
    }
}
