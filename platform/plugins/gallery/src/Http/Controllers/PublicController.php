<?php

namespace Cmat\Gallery\Http\Controllers;

use Cmat\Gallery\Models\Gallery as GalleryModel;
use Cmat\Gallery\Repositories\Interfaces\GalleryInterface;
use Cmat\Gallery\Services\GalleryService;
use Cmat\Theme\Events\RenderingSingleEvent;
use Gallery;
use Illuminate\Routing\Controller;
use SeoHelper;
use SlugHelper;
use Theme;

class PublicController extends Controller
{
    public function __construct(protected GalleryInterface $galleryRepository)
    {
    }

    public function getGalleries()
    {
        Gallery::registerAssets();
        $galleries = $this->galleryRepository->getAll();

        SeoHelper::setTitle(__('Galleries'));

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Galleries'), route('public.galleries'));

        return Theme::scope('galleries', compact('galleries'), 'plugins/gallery::themes.galleries')
            ->render();
    }

    public function getGallery(string $slug, GalleryService $galleryService)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(GalleryModel::class));

        if (! $slug) {
            abort(404);
        }

        $data = $galleryService->handleFrontRoutes($slug);

        if (isset($data['slug']) && $data['slug'] !== $slug->key) {
            return redirect()
                ->to(route('public.single', SlugHelper::getPrefix(GalleryModel::class) . '/' . $data['slug']));
        }

        event(new RenderingSingleEvent($slug));

        return Theme::scope($data['view'], $data['data'], $data['default_view'])
            ->render();
    }
}
