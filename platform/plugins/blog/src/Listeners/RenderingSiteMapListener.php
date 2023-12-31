<?php

namespace Cmat\Blog\Listeners;

use Cmat\Blog\Repositories\Interfaces\CategoryInterface;
use Cmat\Blog\Repositories\Interfaces\PostInterface;
use Cmat\Blog\Repositories\Interfaces\TagInterface;
use SiteMapManager;

class RenderingSiteMapListener
{
    public function __construct(
        protected PostInterface $postRepository,
        protected CategoryInterface $categoryRepository,
        protected TagInterface $tagRepository
    ) {
    }

    public function handle(): void
    {
        $posts = $this->postRepository->getDataSiteMap();

        foreach ($posts as $post) {
            SiteMapManager::add($post->url, $post->updated_at, '0.8');
        }

        $categories = $this->categoryRepository->getDataSiteMap();

        foreach ($categories as $category) {
            SiteMapManager::add($category->url, $category->updated_at, '0.8');
        }

        $tags = $this->tagRepository->getDataSiteMap();

        foreach ($tags as $tag) {
            SiteMapManager::add($tag->url, $tag->updated_at, '0.3', 'weekly');
        }
    }
}
