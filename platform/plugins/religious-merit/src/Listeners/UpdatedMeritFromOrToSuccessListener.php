<?php

namespace Cmat\ReligiousMerit\Listeners;

use Cmat\ReligiousMerit\Events\UpdatedMeritFromOrToSuccessEvent;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProjectInterface;

class UpdatedMeritFromOrToSuccessListener
{
    public function handle(UpdatedMeritFromOrToSuccessEvent $event): void
    {
        $project_id = $event->project_id;

        app(ReligiousMeritProjectInterface::class)
            ->updateProgress($project_id);
    }
}
