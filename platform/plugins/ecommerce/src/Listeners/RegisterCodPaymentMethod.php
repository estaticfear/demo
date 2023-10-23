<?php

namespace Cmat\Ecommerce\Listeners;

use Cmat\Payment\Enums\PaymentMethodEnum;
use PaymentMethods;

class RegisterCodPaymentMethod
{
    public function handle(): void
    {
        PaymentMethods::method(PaymentMethodEnum::COD, [
            'html' => view('plugins/ecommerce::orders.partials.cod')->render(),
            'priority' => 998,
        ]);
    }
}
