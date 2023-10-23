<?php

namespace Cmat\Page\Listeners;

use Cmat\Page\Repositories\Interfaces\PageInterface;
use SiteMapManager;

class RenderingSiteMapListener
{
    public function __construct(protected PageInterface $pageRepository)
    {
    }

    public function handle(): void
    {
        $pages = $this->pageRepository->getDataSiteMap();

        foreach ($pages as $page) {
            SiteMapManager::add($page->url, $page->updated_at, '0.8');
        }
    }
}
