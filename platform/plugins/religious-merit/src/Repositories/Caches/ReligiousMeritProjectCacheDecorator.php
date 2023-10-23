<?php

namespace Cmat\ReligiousMerit\Repositories\Caches;

use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProjectInterface;

class ReligiousMeritProjectCacheDecorator extends CacheAbstractDecorator implements ReligiousMeritProjectInterface
{
    public function getAvailableProjects($keyword = '', $limit = 10, $paginate = 10)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getAvailableProjectsByCategory($categoryId, $keyword = '', $limit = 10, $paginate = 10)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getFeaturedProjects($keyword = '', $limit = 10, $paginate = 10)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getFinishedProjects($keyword = '', $limit = 10, $paginate = 10)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getAvailableProjectDetail($id)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getProjectsRelated($project, $limit = 3)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getAllActiveProjectsLabels() {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function updateProgress($project_id) {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getProjectMerits($project_id, $keyword = '', $type = '', $limit = 10, $paginate = 10) {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
