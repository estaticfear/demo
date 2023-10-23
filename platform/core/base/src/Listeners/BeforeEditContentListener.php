<?php

namespace Cmat\Base\Listeners;

use Cmat\Base\Events\BeforeEditContentEvent;
use Exception;

class BeforeEditContentListener
{
    public function handle(BeforeEditContentEvent $event): void
    {
        try {
            do_action(BASE_ACTION_BEFORE_EDIT_CONTENT, $event->request, $event->data);
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
