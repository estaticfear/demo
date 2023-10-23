<?php

namespace Cmat\Vnpay\Enums;

use Cmat\Base\Supports\Enum;
use Html;
use Illuminate\Support\HtmlString;

/**
 * @method static VnpayTransactionStatusEnum SUCCESS()
 * @method static VnpayTransactionStatusEnum READ()
 * @method static VnpayTransactionStatusEnum FAIL()
 * @method static VnpayTransactionStatusEnum NOT_COMPLETED()
 */
class VnpayTransactionStatusEnum extends Enum
{
    public const NEW = 'new';
    public const SUCCESS = 'success';
    public const NOT_COMPLETED = 'not-completed';
    public const FAIL = 'fail';

    public static $langPath = 'plugins/vnpay::vnpay.statuses';

    public function toHtml(): HtmlString|string
    {
        return match ($this->value) {
            self::SUCCESS => Html::tag('span', self::SUCCESS()->label(), ['class' => 'label-success status-label'])
                ->toHtml(),
            self::NEW => Html::tag('span', self::NEW()->label(), ['class' => 'label-info status-label'])
                ->toHtml(),
            self::NOT_COMPLETED => Html::tag('span', self::NOT_COMPLETED()->label(), ['class' => 'label-info status-label'])
                ->toHtml(),
            self::FAIL => Html::tag('span', self::FAIL()->label(), ['class' => 'label-warning status-label'])
                ->toHtml(),
            default => parent::toHtml(),
        };
    }
}
