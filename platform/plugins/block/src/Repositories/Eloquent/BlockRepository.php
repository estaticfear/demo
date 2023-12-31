<?php

namespace Cmat\Block\Repositories\Eloquent;

use Cmat\Support\Repositories\Eloquent\RepositoriesAbstract;
use Cmat\Block\Repositories\Interfaces\BlockInterface;
use Illuminate\Support\Str;

class BlockRepository extends RepositoriesAbstract implements BlockInterface
{
    public function createSlug(?string $name, int|string|null $id): string
    {
        $slug = Str::slug($name);
        $index = 1;
        $baseSlug = $slug;
        while ($this->model->where('alias', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $baseSlug . '-' . $index++;
        }

        if (empty($slug)) {
            $slug = time();
        }

        $this->resetModel();

        return $slug;
    }
}
