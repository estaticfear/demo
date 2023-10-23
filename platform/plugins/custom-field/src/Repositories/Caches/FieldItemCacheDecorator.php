<?php

namespace Cmat\CustomField\Repositories\Caches;

use Cmat\CustomField\Models\FieldItem;
use Cmat\CustomField\Repositories\Interfaces\FieldItemInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;
use Illuminate\Support\Collection;

class FieldItemCacheDecorator extends CacheAbstractDecorator implements FieldItemInterface
{
    public function deleteFieldItem(int|string|null $id): int
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    public function getGroupItems(int|string|null $groupId, int|string|null $parentId = null): Collection
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function updateWithUniqueSlug(int|string|null $id, array $data): ?FieldItem
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}
