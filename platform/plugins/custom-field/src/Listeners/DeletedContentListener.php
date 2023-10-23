<?php

namespace Cmat\CustomField\Listeners;

use Cmat\Base\Events\DeletedContentEvent;
use CustomField;
use Exception;

class DeletedContentListener
{
    public function handle(DeletedContentEvent $event): void
    {
        try {
            CustomField::deleteCustomFields($event->data);
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
