<?php

namespace Cmat\ReligiousMerit\Listeners;

use Cmat\ReligiousMerit\Enums\ReligiousMeritStatusEnum;
use Cmat\ReligiousMerit\Events\UpdatedMeritFromOrToSuccessEvent;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritInterface;
use Cmat\Vnpay\Enums\VnpayTransactionStatusEnum;
use Cmat\Vnpay\Events\VnpayTransactionUpdatedEvent;

class VnpayTransactionUpdatedListener
{
    public function handle(VnpayTransactionUpdatedEvent $event): void
    {
        $vnp_status = $event->data->status;
        $vnp_target_id = $event->data->target_id;
        $vnp_target_type = $event->data->target_type;

        $status = false;

        if ($vnp_status == VnpayTransactionStatusEnum::SUCCESS) {
            $status = ReligiousMeritStatusEnum::SUCCESS;
        }

        if ($vnp_status == VnpayTransactionStatusEnum::FAIL) {
            $status = ReligiousMeritStatusEnum::FAIL;
        }

        if ($status) {
            app(ReligiousMeritInterface::class)
                ->updatePaymentStatus($vnp_target_id, $status);
        }

        if ($status == ReligiousMeritStatusEnum::SUCCESS) {
            $merit = app(ReligiousMeritInterface::class)->findById($vnp_target_id);
            event(new UpdatedMeritFromOrToSuccessEvent($merit->project_id));
        }
    }
}
