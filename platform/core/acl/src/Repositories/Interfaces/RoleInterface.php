<?php

namespace Cmat\ACL\Repositories\Interfaces;

use Cmat\Support\Repositories\Interfaces\RepositoryInterface;

interface RoleInterface extends RepositoryInterface
{
    public function createSlug(string $name, int|string $id): string;
}
