<?php

namespace Cmat\Blog\Services;

use Cmat\Blog\Models\Post;
use Cmat\Blog\Services\Abstracts\StoreCategoryServiceAbstract;
use Illuminate\Http\Request;

class StoreCategoryService extends StoreCategoryServiceAbstract
{
    public function execute(Request $request, Post $post): void
    {
        $categories = $request->input('categories');
        if (! empty($categories) && is_array($categories)) {
            $post->categories()->sync($categories);
        }
    }
}
