<?php

namespace Cmat\AuditLog\Listeners;

use Cmat\AuditLog\Events\AuditHandlerEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Exception;
use AuditLog;

class DeletedContentListener
{
    public function handle(DeletedContentEvent $event): void
    {
        try {
            if ($event->data->id) {
                event(new AuditHandlerEvent(
                    $event->screen,
                    'deleted',
                    $event->data->id,
                    AuditLog::getReferenceName($event->screen, $event->data),
                    'danger'
                ));
            }
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
