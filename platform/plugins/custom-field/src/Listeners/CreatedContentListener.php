<?php

namespace Cmat\CustomField\Listeners;

use Cmat\Base\Events\CreatedContentEvent;
use CustomField;
use Exception;

class CreatedContentListener
{
    public function handle(CreatedContentEvent $event): void
    {
        try {
            CustomField::saveCustomFields($event->request, $event->data);
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
