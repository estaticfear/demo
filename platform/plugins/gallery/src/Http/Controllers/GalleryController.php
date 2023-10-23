<?php

namespace Cmat\Gallery\Http\Controllers;

use Cmat\Base\Events\BeforeEditContentEvent;
use Cmat\Base\Forms\FormBuilder;
use Cmat\Base\Http\Controllers\BaseController;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Base\Traits\HasDeleteManyItemsTrait;
use Cmat\Gallery\Forms\GalleryForm;
use Cmat\Gallery\Tables\GalleryTable;
use Cmat\Gallery\Http\Requests\GalleryRequest;
use Cmat\Gallery\Repositories\Interfaces\GalleryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;

class GalleryController extends BaseController
{
    use HasDeleteManyItemsTrait;

    public function __construct(protected GalleryInterface $galleryRepository)
    {
    }

    public function index(GalleryTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/gallery::gallery.galleries'));

        return $dataTable->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/gallery::gallery.create'));

        return $formBuilder->create(GalleryForm::class)->renderForm();
    }

    public function store(GalleryRequest $request, BaseHttpResponse $response)
    {
        $gallery = $this->galleryRepository->getModel();
        $gallery->fill($request->input());
        $gallery->user_id = Auth::id();

        $gallery = $this->galleryRepository->createOrUpdate($gallery);

        event(new CreatedContentEvent(GALLERY_MODULE_SCREEN_NAME, $request, $gallery));

        return $response
            ->setPreviousUrl(route('galleries.index'))
            ->setNextUrl(route('galleries.edit', $gallery->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $gallery = $this->galleryRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $gallery));

        page_title()->setTitle(trans('plugins/gallery::gallery.edit') . ' "' . $gallery->name . '"');

        return $formBuilder->create(GalleryForm::class, ['model' => $gallery])->renderForm();
    }

    public function update(int|string $id, GalleryRequest $request, BaseHttpResponse $response)
    {
        $gallery = $this->galleryRepository->findOrFail($id);
        $gallery->fill($request->input());

        $this->galleryRepository->createOrUpdate($gallery);

        event(new UpdatedContentEvent(GALLERY_MODULE_SCREEN_NAME, $request, $gallery));

        return $response
            ->setPreviousUrl(route('galleries.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $gallery = $this->galleryRepository->findOrFail($id);
            $this->galleryRepository->delete($gallery);
            event(new DeletedContentEvent(GALLERY_MODULE_SCREEN_NAME, $request, $gallery));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        return $this->executeDeleteItems($request, $response, $this->galleryRepository, GALLERY_MODULE_SCREEN_NAME);
    }
}
