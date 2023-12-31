<?php

namespace Cmat\Language\Listeners;

use Cmat\Setting\Repositories\Interfaces\SettingInterface;
use Cmat\Theme\Events\ThemeRemoveEvent;
use Cmat\Widget\Repositories\Interfaces\WidgetInterface;
use Exception;
use Language;

class ThemeRemoveListener
{
    public function __construct(protected WidgetInterface $widgetRepository, protected SettingInterface $settingRepository)
    {
    }

    public function handle(ThemeRemoveEvent $event): void
    {
        try {
            $languages = Language::getActiveLanguage(['lang_code']);

            foreach ($languages as $language) {
                $themeNameByLanguage = $event->theme . '-' . $language->lang_code;

                $this->widgetRepository->deleteBy(['theme' => $themeNameByLanguage]);

                $this->settingRepository->deleteBy(['key', 'like', 'theme-' . $themeNameByLanguage . '-%']);
            }
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
