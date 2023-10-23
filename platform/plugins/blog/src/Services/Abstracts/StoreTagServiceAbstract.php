<?php

namespace Cmat\Blog\Services\Abstracts;

use Cmat\Blog\Models\Post;
use Cmat\Blog\Repositories\Interfaces\TagInterface;
use Illuminate\Http\Request;

abstract class StoreTagServiceAbstract
{
    public function __construct(protected TagInterface $tagRepository)
    {
    }

    abstract public function execute(Request $request, Post $post): void;
}
