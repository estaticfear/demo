<?php

namespace Cmat\Page\Models;

use Cmat\ACL\Models\User;
use Cmat\Base\Casts\SafeContent;
use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Models\BaseModel;
use Cmat\Revision\RevisionableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends BaseModel
{
    use RevisionableTrait;

    protected $table = 'pages';

    protected bool $revisionEnabled = true;

    protected bool $revisionCleanup = true;

    protected int $historyLimit = 20;

    protected array $dontKeepRevisionOf = ['content'];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'content' => SafeContent::class,
        'name' => SafeContent::class,
        'description' => SafeContent::class,
    ];

    protected $fillable = [
        'name',
        'content',
        'image',
        'template',
        'description',
        'status',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
