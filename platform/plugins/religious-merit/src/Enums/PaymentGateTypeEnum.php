<?php

namespace Cmat\ReligiousMerit\Enums;

use Cmat\Base\Supports\Enum;
use Html;
use Illuminate\Support\HtmlString;

/**
 * @method static ReligiousTypeEnum VNPAY()
 * @method static ReligiousTypeEnum TRANSFER()
 * @method static ReligiousTypeEnum CASH()
 */
class PaymentGateTypeEnum extends Enum
{
    public const VNPAY = 'vnpay';

    public const TRANSFER = 'transfer';

    public const CASH = 'cash';

    public static $langPath = 'plugins/religious-merit::enums.payment-gates';

    public function toHtml(): string|HtmlString
    {
        return match ($this->value) {
            self::VNPAY => Html::tag('span', self::VNPAY()->label(), ['class' => 'label-info status-label'])
                ->toHtml(),
            self::TRANSFER => Html::tag('span', self::TRANSFER()->label(), ['class' => 'label-warning status-label'])
                ->toHtml(),
            self::CASH => Html::tag('span', self::CASH()->label(), ['class' => 'label-success status-label'])
                ->toHtml(),
            default => parent::toHtml(),
        };
    }

}
