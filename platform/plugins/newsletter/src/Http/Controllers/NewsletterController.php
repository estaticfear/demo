<?php

namespace Cmat\Newsletter\Http\Controllers;

use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Http\Controllers\BaseController;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Base\Traits\HasDeleteManyItemsTrait;
use Cmat\Newsletter\Repositories\Interfaces\NewsletterInterface;
use Cmat\Newsletter\Tables\NewsletterTable;
use Exception;
use Illuminate\Http\Request;

class NewsletterController extends BaseController
{
    use HasDeleteManyItemsTrait;

    public function __construct(protected NewsletterInterface $newsletterRepository)
    {
    }

    public function index(NewsletterTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/newsletter::newsletter.name'));

        return $dataTable->renderTable();
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $newsletter = $this->newsletterRepository->findOrFail($id);
            $this->newsletterRepository->delete($newsletter);

            event(new DeletedContentEvent(NEWSLETTER_MODULE_SCREEN_NAME, $request, $newsletter));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        return $this->executeDeleteItems(
            $request,
            $response,
            $this->newsletterRepository,
            NEWSLETTER_MODULE_SCREEN_NAME
        );
    }
}
