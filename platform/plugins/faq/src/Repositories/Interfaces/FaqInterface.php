<?php

namespace Cmat\Faq\Repositories\Interfaces;

use Cmat\Support\Repositories\Interfaces\RepositoryInterface;

interface FaqInterface extends RepositoryInterface
{
      /**
     * @param int $perPage
     * @return mixed
     */
    public function getAllActiveFaqs($perPage = 12);
}
