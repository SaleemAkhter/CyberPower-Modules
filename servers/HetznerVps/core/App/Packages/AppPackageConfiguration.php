<?php

namespace ModulesGarden\Servers\HetznerVps\Core\App\Packages;


use ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\Config\Enum;

abstract class AppPackageConfiguration
{
    const PACKAGE_STATUS = 'packageStatus';
    const PACKAGE_STATUS_ACTIVE = 'active';
    const PACKAGE_STATUS_INACTIVE = 'inactive';

    const APP_CONFIGURATION = [];

    public function getSuboptionsByCallback($optName = null)
    {

        $optName = preg_replace('/\s+/', '', $optName);
        $fullOptName = $optName . 'GetSubOptions';
        if (method_exists($this, $fullOptName))
        {
            return $this->{$fullOptName}();
        }

        return false;
    }

    public function getConfigurableOptionsList()
    {
        if (method_exists($this, 'getConfigurableOptions'))
        {
            return $this->getConfigurableOptions();
        }

        return $this::APP_CONFIGURATION[Enum::CONFIGURABLE_OPTIONS];
    }
}

