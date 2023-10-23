<?php

namespace Cmat\RequestLog\Models;

use Cmat\Base\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Query\Builder;

class RequestLog extends BaseModel
{
    use MassPrunable;

    protected $table = 'request_logs';

    protected $fillable = [
        'url',
        'status_code',
    ];

    protected $casts = [
        'referrer' => 'json',
        'user_id' => 'json',
    ];

    public function prunable(): Builder
    {
        return $this->whereDate('created_at', '>', Carbon::now()->subDays(30)->toDateString());
    }
}
