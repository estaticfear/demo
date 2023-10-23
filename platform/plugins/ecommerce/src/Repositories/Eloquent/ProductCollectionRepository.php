<?php

namespace Cmat\Ecommerce\Repositories\Eloquent;

use Cmat\Ecommerce\Repositories\Interfaces\ProductCollectionInterface;
use Cmat\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Support\Str;

class ProductCollectionRepository extends RepositoriesAbstract implements ProductCollectionInterface
{
    public function createSlug($name, $id)
    {
        $slug = Str::slug($name);
        $index = 1;
        $baseSlug = $slug;
        while ($this->model->where('slug', $slug)->where('id', '!=', $id)->count() > 0) {
            $slug = $baseSlug . '-' . $index++;
        }

        if (empty($slug)) {
            $slug = time();
        }

        $this->resetModel();

        return $slug;
    }
}
