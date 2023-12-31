<?php

namespace Cmat\CustomField\Repositories\Caches;

use Cmat\CustomField\Repositories\Interfaces\FieldGroupInterface;
use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;

class FieldGroupCacheDecorator extends CacheAbstractDecorator implements FieldGroupInterface
{
    public function getFieldGroups(array $condition = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function createFieldGroup(array $data)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    public function createOrUpdateFieldGroup($id, array $data)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    public function updateFieldGroup($id, array $data)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    public function getFieldGroupItems(
        $groupId,
        $parentId = null,
        $withValue = false,
        $morphClass = null,
        $morphId = null
    ) {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
