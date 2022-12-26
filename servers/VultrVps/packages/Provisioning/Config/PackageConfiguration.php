<?php

namespace ModulesGarden\Servers\VultrVps\Packages\Provisioning\Config;

use ModulesGarden\Servers\VultrVps\Core\App\Packages\BasePackageConfiguration;
use ModulesGarden\Servers\VultrVps\Core\App\Packages\PackageConfigurationConst;

class PackageConfiguration extends BasePackageConfiguration
{
    const CONFIGURATION =
    [
        PackageConfigurationConst::PACKAGE_NAME => 'Provisioning',
        PackageConfigurationConst::VERSION => '1.0.0'
    ];
}
