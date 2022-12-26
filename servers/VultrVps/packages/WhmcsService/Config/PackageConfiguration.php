<?php

namespace ModulesGarden\Servers\VultrVps\Packages\WhmcsService\Config;

use ModulesGarden\Servers\VultrVps\Core\App\Packages\BasePackageConfiguration;
use ModulesGarden\Servers\VultrVps\Core\App\Packages\PackageConfigurationConst;

class PackageConfiguration extends BasePackageConfiguration
{
    const CONFIGURATION =
    [
        PackageConfigurationConst::PACKAGE_NAME => 'WhmcsService',
        PackageConfigurationConst::VERSION => '1.0.0'
    ];

    public static function getPackageName()
    {
        return self::CONFIGURATION[PackageConfigurationConst::PACKAGE_NAME];
    }
}
