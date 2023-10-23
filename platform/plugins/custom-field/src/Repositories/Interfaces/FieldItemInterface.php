<?php

namespace Cmat\CustomField\Repositories\Interfaces;

use Cmat\CustomField\Models\FieldItem;
use Cmat\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\Collection;

interface FieldItemInterface extends RepositoryInterface
{
    public function deleteFieldItem(int|string|null $id): int;

    public function getGroupItems(int|string|null $groupId, int|string|null $parentId = null): Collection;

    public function updateWithUniqueSlug(int|string|null $id, array $data): ?FieldItem;
}
