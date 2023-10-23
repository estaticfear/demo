<?php

namespace Cmat\Base\Listeners;

use Cmat\Base\Events\AdminNotificationEvent;
use Cmat\Base\Models\AdminNotification;

class AdminNotificationListener
{
    public function handle(AdminNotificationEvent $event): void
    {
        $item = $event->item;

        AdminNotification::create([
            'title' => $item->getTitle(),
            'action_label' => $item->getLabel(),
            'action_url' => $item->getRoute(),
            'description' => $item->getDescription(),
        ]);
    }
}
