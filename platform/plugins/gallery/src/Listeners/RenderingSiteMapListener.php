<?php

namespace Cmat\Gallery\Listeners;

use SiteMapManager;
use Cmat\Gallery\Repositories\Interfaces\GalleryInterface;

class RenderingSiteMapListener
{
    public function __construct(protected GalleryInterface $galleryRepository)
    {
    }

    public function handle(): void
    {
        SiteMapManager::add(route('public.galleries'), '2020-11-15 00:00', '0.8', 'weekly');

        $galleries = $this->galleryRepository->getDataSiteMap();

        foreach ($galleries as $gallery) {
            SiteMapManager::add($gallery->url, $gallery->updated_at, '0.8');
        }
    }
}
