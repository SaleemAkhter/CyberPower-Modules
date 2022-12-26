<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Packages\Provisioning\Config;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Packages\BasePackageConfiguration;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Packages\PackageConfigurationConst;

class PackageConfiguration extends BasePackageConfiguration
{
    const CONFIGURATION =
    [
        PackageConfigurationConst::PACKAGE_NAME => 'Provisioning',
        PackageConfigurationConst::VERSION => '1.0.0'
    ];
}
