<?php

namespace ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\Traits;

use ModulesGarden\Servers\HetznerVps\Core\App\Packages\PackageManager;
use ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\Config\Enum;
use ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\Config\PackageConfiguration;

trait ConfigurableOptionsConfig
{
    protected $configOptionsList = [];

    protected function loadConfigurableOptionsList()
    {
        if (!$this->configOptionsList) {
            $packageManager = new PackageManager();
            $config = $packageManager->getPackageConfiguration(PackageConfiguration::getPackageName());
            $appConfigInstance = $config->getAppConfigInstance();
            $this->configOptionsList = $appConfigInstance->getConfigurableOptions();
        }
    }

    public function trimConfigOptionName($name = null)
    {
        if (is_string($name) && trim($name) !== '' && stripos($name, '|') > 0) {
            $parts = explode('|', $name);

            return $parts[0];
        }

        return $name;
    }

    public function getConfigurableOptionConfigParams($optionName = null)
    {
        $this->loadConfigurableOptionsList();

        foreach ($this->configOptionsList as $optConfig) {
            if (!$optConfig || !is_array($optConfig)) {
                continue;
            }

            if ($optConfig[Enum::OPTION_NAME] === $optionName || $this->trimConfigOptionName($optConfig[Enum::OPTION_NAME]) === $optionName) {
                return $optConfig;
            }
        }
        return false;
    }
}
