<?php

namespace Cmat\Member\Http\Resources;

use Cmat\Member\Models\MemberActivityLog;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MemberActivityLog
 */
class ActivityLogResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'ip_address' => $this->ip_address,
            'description' => $this->getDescription(),
        ];
    }
}
