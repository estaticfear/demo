<?php

namespace Cmat\Gallery\Listeners;

use Cmat\Base\Events\DeletedContentEvent;
use Exception;
use Gallery;

class DeletedContentListener
{
    public function handle(DeletedContentEvent $event): void
    {
        try {
            Gallery::deleteGallery($event->data);
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
