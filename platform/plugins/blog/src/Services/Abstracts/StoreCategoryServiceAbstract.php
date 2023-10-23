<?php

namespace Cmat\Blog\Services\Abstracts;

use Cmat\Blog\Models\Post;
use Cmat\Blog\Repositories\Interfaces\CategoryInterface;
use Illuminate\Http\Request;

abstract class StoreCategoryServiceAbstract
{
    public function __construct(protected CategoryInterface $categoryRepository)
    {
    }

    abstract public function execute(Request $request, Post $post): void;
}
