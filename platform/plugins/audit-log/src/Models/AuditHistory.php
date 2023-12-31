<?php

namespace Cmat\AuditLog\Models;

use Cmat\ACL\Models\User;
use Cmat\Base\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

class AuditHistory extends BaseModel
{
    use MassPrunable;

    protected $table = 'audit_histories';

    protected $fillable = [
        'user_agent',
        'ip_address',
        'module',
        'action',
        'user_id',
        'reference_user',
        'reference_id',
        'reference_name',
        'type',
        'request',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function prunable(): Builder
    {
        return $this->whereDate('created_at', '>', Carbon::now()->subDays(30)->toDateString());
    }
}
