<?php

namespace Cmat\RequestLog\Events;

use Cmat\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class RequestHandlerEvent extends Event
{
    use SerializesModels;

    public int $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }
}
