<?php

namespace Cmat\Gallery\Listeners;

use Cmat\Base\Events\UpdatedContentEvent;
use Exception;
use Gallery;

class UpdatedContentListener
{
    public function handle(UpdatedContentEvent $event): void
    {
        try {
            Gallery::saveGallery($event->request, $event->data);
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
