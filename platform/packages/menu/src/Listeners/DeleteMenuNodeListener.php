<?php

namespace Cmat\Menu\Listeners;

use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Menu\Repositories\Interfaces\MenuNodeInterface;
use Exception;
use Menu;

class DeleteMenuNodeListener
{
    public function __construct(protected MenuNodeInterface $menuNodeRepository)
    {
    }

    public function handle(DeletedContentEvent $event): void
    {
        if (in_array(get_class($event->data), Menu::getMenuOptionModels())) {
            try {
                $this->menuNodeRepository->deleteBy([
                    'reference_id' => $event->data->id,
                    'reference_type' => get_class($event->data),
                ]);
            } catch (Exception $exception) {
                info($exception->getMessage());
            }
        }
    }
}
