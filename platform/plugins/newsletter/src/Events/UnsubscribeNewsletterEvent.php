<?php

namespace Cmat\Newsletter\Events;

use Cmat\Newsletter\Models\Newsletter;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UnsubscribeNewsletterEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Newsletter $newsletter;

    public function __construct(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
    }
}
