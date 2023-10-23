<?php

namespace Cmat\PluginManagement\Commands;

use BaseHelper;
use Cmat\PluginManagement\Services\PluginService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('cms:plugin:deactivate:all', 'Deactivate all plugins in /plugins directory')]
class PluginDeactivateAllCommand extends Command
{
    public function handle(PluginService $pluginService): int
    {
        foreach (BaseHelper::scanFolder(plugin_path()) as $plugin) {
            $pluginService->deactivate($plugin);
        }

        $this->components->info('Deactivated successfully!');

        return self::SUCCESS;
    }
}
