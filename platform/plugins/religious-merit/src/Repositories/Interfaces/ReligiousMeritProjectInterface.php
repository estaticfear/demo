<?php

namespace Cmat\ReligiousMerit\Repositories\Interfaces;

use Cmat\Support\Repositories\Interfaces\RepositoryInterface;

interface ReligiousMeritProjectInterface extends RepositoryInterface
{
    /**
     * @param string $query
     * @param int $limit
     * @param int $paginate
     * @return mixed
     */
    public function getAvailableProjects($query = '', $limit = 10, $paginate = 10);

    /**
     * @param string $categoryId
     * @param string $query
     * @param int $limit
     * @param int $paginate
     * @return mixed
     */
    public function getAvailableProjectsByCategory($categoryId, $query = '', $limit = 10, $paginate = 10);


    /**
     * @param string $query
     * @param int $limit
     * @param int $paginate
     * @return mixed
     */
    public function getFeaturedProjects($query = '', $limit = 10, $paginate = 10);

    /**
     * @param string $query
     * @param int $limit
     * @param int $paginate
     * @return mixed
     */
    public function getFinishedProjects($query = '', $limit = 10, $paginate = 10);

    /**
     * @param int|string $id
     * @return mixed
     */
    public function getAvailableProjectDetail($id);

    /**
     * @return mixed
     */
    public function getProjectsRelated($project, $limit = 3);

    public function getAllActiveProjectsLabels();

    public function updateProgress($project_id);

    public function getProjectMerits($project_id, $query = '', $type = '', $limit = 10, $paginate = 10);
}
