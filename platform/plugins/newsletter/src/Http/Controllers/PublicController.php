<?php

namespace Cmat\Newsletter\Http\Controllers;

use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Newsletter\Enums\NewsletterStatusEnum;
use Cmat\Newsletter\Events\SubscribeNewsletterEvent;
use Cmat\Newsletter\Events\UnsubscribeNewsletterEvent;
use Cmat\Newsletter\Http\Requests\NewsletterRequest;
use Cmat\Newsletter\Repositories\Interfaces\NewsletterInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;

class PublicController extends Controller
{
    public function __construct(protected NewsletterInterface $newsletterRepository)
    {
    }

    public function postSubscribe(NewsletterRequest $request, BaseHttpResponse $response)
    {
        $newsletter = $this->newsletterRepository->getFirstBy(['email' => $request->input('email')]);
        if (! $newsletter) {
            $newsletter = $this->newsletterRepository->createOrUpdate($request->input());

            event(new SubscribeNewsletterEvent($newsletter));
        }

        return $response->setMessage(__('Subscribe to newsletter successfully!'));
    }

    public function getUnsubscribe(int|string $id, Request $request, BaseHttpResponse $response)
    {
        if (! URL::hasValidSignature($request)) {
            abort(404);
        }

        $newsletter = $this->newsletterRepository->getFirstBy([
            'id' => $id,
            'status' => NewsletterStatusEnum::SUBSCRIBED,
        ]);

        if ($newsletter) {
            $newsletter->status = NewsletterStatusEnum::UNSUBSCRIBED;
            $this->newsletterRepository->createOrUpdate($newsletter);

            event(new UnsubscribeNewsletterEvent($newsletter));

            return $response
                ->setNextUrl(route('public.index'))
                ->setMessage(__('Unsubscribe to newsletter successfully'));
        }

        return $response
            ->setError()
            ->setNextUrl(route('public.index'))
            ->setMessage(__('Your email does not exist in the system or you have unsubscribed already!'));
    }
}
