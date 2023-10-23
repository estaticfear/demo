<?php

namespace Cmat\ReligiousMerit\Enums;

use Cmat\Base\Supports\Enum;
use Html;
use Illuminate\Support\HtmlString;

/**
 * @method static ReligiousTypeEnum ARTIFACT()
 * @method static ReligiousTypeEnum EFFORT()
 * @method static ReligiousTypeEnum MONEY()
 */
class ReligiousTypeEnum extends Enum
{
    public const ARTIFACT = 'artifact';
    public const EFFORT = 'effort';
    public const MONEY = 'money';

    public static $langPath = 'plugins/religious-merit::enums.religious-types';

    public function toHtml(): string|HtmlString
    {
        return match ($this->value) {
            self::ARTIFACT => Html::tag('span', self::ARTIFACT()->label(), ['class' => 'label-info status-label'])
                ->toHtml(),
            self::EFFORT => Html::tag('span', self::EFFORT()->label(), ['class' => 'label-warning status-label'])
                ->toHtml(),
            self::MONEY => Html::tag('span', self::MONEY()->label(), ['class' => 'label-success status-label'])
                ->toHtml(),
            default => parent::toHtml(),
        };
    }

}
