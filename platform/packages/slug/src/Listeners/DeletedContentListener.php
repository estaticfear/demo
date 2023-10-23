<?php

namespace Cmat\Slug\Listeners;

use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Slug\Repositories\Interfaces\SlugInterface;
use Exception;
use SlugHelper;

class DeletedContentListener
{
    public function __construct(protected SlugInterface $slugRepository)
    {
    }

    public function handle(DeletedContentEvent $event): void
    {
        if (SlugHelper::isSupportedModel(get_class($event->data))) {
            try {
                $this->slugRepository->deleteBy([
                    'reference_id' => $event->data->id,
                    'reference_type' => get_class($event->data),
                ]);
            } catch (Exception $exception) {
                info($exception->getMessage());
            }
        }
    }
}
