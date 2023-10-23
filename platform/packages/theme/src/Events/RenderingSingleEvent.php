<?php

namespace Cmat\Theme\Events;

use Cmat\Base\Events\Event;
use Cmat\Slug\Models\Slug;
use Illuminate\Queue\SerializesModels;

class RenderingSingleEvent extends Event
{
    use SerializesModels;

    public Slug $slug;

    public function __construct(Slug $slug)
    {
        $this->slug = $slug;
    }
}
