<?php

namespace Cmat\ReligiousMerit\Enums;

use Cmat\Base\Supports\Enum;
use Html;
use Illuminate\Support\HtmlString;

/**
 * @method static ReligiousMeritStatusEnum FINISHED()
 * @method static ReligiousMeritStatusEnum PENDING()
 * @method static ReligiousMeritStatusEnum IN_PROGRESS()
 */
class ReligiousMeritProjectCostEstimationStatusEnum extends Enum
{
    public const FINISHED = 'finished';
    public const PENDING = 'pending';
    public const IN_PROGRESS = 'in-progress';

    public static $langPath = 'plugins/religious-merit::enums.religious-status';

    public function toHtml(): string|HtmlString
    {
        return match ($this->value) {
            self::FINISHED => Html::tag('span', self::FINISHED()->label(), ['class' => 'label-success status-label'])
                ->toHtml(),
            self::PENDING => Html::tag('span', self::PENDING()->label(), ['class' => 'label-info status-label'])
                ->toHtml(),
            self::IN_PROGRESS => Html::tag('span', self::IN_PROGRESS()->label(), ['class' => 'label-info status-label'])
                ->toHtml(),
            default => parent::toHtml(),
        };
    }

}
