<?php

namespace Cmat\ReligiousMerit\Enums;

use Cmat\Base\Supports\Enum;
use Html;
use Illuminate\Support\HtmlString;

/**
 * @method static ReligiousMeritStatusEnum SUCCESS()
 * @method static ReligiousMeritStatusEnum FAIL()
 * @method static ReligiousMeritStatusEnum IN_PROGRESS()
 * @method static ReligiousMeritStatusEnum IS_BOOKED()
 */
class ReligiousMeritStatusEnum extends Enum
{
    public const SUCCESS = 'success';
    public const FAIL = 'fail';
    public const IN_PROGRESS = 'in-progress';
    public const IS_BOOKED = 'is-booked';
    public const CANCELED = 'canceled';

    public static $langPath = 'plugins/religious-merit::enums.religious-status';

    public function toHtml(): string|HtmlString
    {
        return match ($this->value) {
            self::SUCCESS => Html::tag('span', self::SUCCESS()->label(), ['class' => 'label-success status-label'])
                ->toHtml(),
            self::FAIL => Html::tag('span', self::FAIL()->label(), ['class' => 'label-danger status-label'])
                ->toHtml(),
            self::IN_PROGRESS => Html::tag('span', self::IN_PROGRESS()->label(), ['class' => 'label-info status-label'])
                ->toHtml(),
            self::IS_BOOKED => Html::tag('span', self::IS_BOOKED()->label(), ['class' => 'status-label', 'style' => 'background-color: #2300e1'])
                ->toHtml(),
            self::CANCELED => Html::tag('span', self::CANCELED()->label(), ['class' => 'status-label', 'style' => 'background-color: #333; color: #fff'])
                ->toHtml(),
            default => parent::toHtml(),
        };
    }

}
