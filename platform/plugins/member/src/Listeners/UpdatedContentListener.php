<?php

namespace Cmat\Member\Listeners;

use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Member\Models\Member;
use Cmat\Member\Repositories\Interfaces\MemberActivityLogInterface;
use Exception;

class UpdatedContentListener
{
    public function handle(UpdatedContentEvent $event): void
    {
        try {
            if ($event->data->id &&
                $event->data->author_type === Member::class &&
                auth('member')->check() &&
                $event->data->author_id == auth('member')->id()
            ) {
                app(MemberActivityLogInterface::class)->createOrUpdate([
                    'action' => 'your_post_updated_by_admin',
                    'reference_name' => $event->data->name,
                    'reference_url' => route('public.member.posts.edit', $event->data->id),
                ]);
            }
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
