<?php

namespace Cmat\Optimize\Facades;

use Cmat\Optimize\Supports\Optimizer;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\Optimize\Supports\Optimizer
 */
class OptimizerFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Optimizer::class;
    }
}
