<?php

namespace Cmat\ReligiousMerit\Providers;

use Cmat\ReligiousMerit\Models\ReligiousMeritProjectCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Menu;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (defined('MENU_ACTION_SIDEBAR_OPTIONS')) {
            Menu::addMenuOptionModel(ReligiousMeritProjectCategory::class);
            add_action(MENU_ACTION_SIDEBAR_OPTIONS, [$this, 'registerMenuOptions'], 2);
        }
    }

    /**
     * Register sidebar options in menu
     */
    public function registerMenuOptions(): void
    {
        if (Auth::user()->hasPermission('religious-merit-project-category.index')) {
            Menu::registerMenuOptions(ReligiousMeritProjectCategory::class, trans('plugins/religious-merit::religious-merit.menu.categories'));
        }
    }
}
