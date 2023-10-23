<?php

namespace Cmat\ReligiousMerit\Events;

use Cmat\Base\Events\Event;
use Eloquent;
use Illuminate\Queue\SerializesModels;

class UpdatedMeritFromOrToSuccessEvent extends Event
{
    use SerializesModels;

    public $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }
}
