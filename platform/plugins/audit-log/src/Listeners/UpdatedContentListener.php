<?php

namespace Cmat\AuditLog\Listeners;

use Cmat\AuditLog\Events\AuditHandlerEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Exception;
use AuditLog;

class UpdatedContentListener
{
    public function handle(UpdatedContentEvent $event): void
    {
        try {
            if ($event->data->id) {
                event(new AuditHandlerEvent(
                    $event->screen,
                    'updated',
                    $event->data->id,
                    AuditLog::getReferenceName($event->screen, $event->data),
                    'primary'
                ));
            }
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
