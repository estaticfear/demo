<?php

namespace Cmat\Block\Repositories\Interfaces;

use Cmat\Support\Repositories\Interfaces\RepositoryInterface;

interface BlockInterface extends RepositoryInterface
{
    public function createSlug(?string $name, int|string|null $id): string;
}
