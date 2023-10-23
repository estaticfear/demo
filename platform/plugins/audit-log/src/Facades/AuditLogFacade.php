<?php

namespace Cmat\AuditLog\Facades;

use Cmat\AuditLog\AuditLog;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Cmat\AuditLog\AuditLog
 */
class AuditLogFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AuditLog::class;
    }
}
