<?php

namespace ModulesGarden\Servers\AwsEc2\Packages\Provisioning\Config;

use ModulesGarden\Servers\AwsEc2\Core\App\Packages\BasePackageConfiguration;
use ModulesGarden\Servers\AwsEc2\Core\App\Packages\PackageConfigurationConst;

class PackageConfiguration extends BasePackageConfiguration
{
    const CONFIGURATION =
    [
        PackageConfigurationConst::PACKAGE_NAME => 'Provisioning',
        PackageConfigurationConst::VERSION => '1.3.3'
    ];
}
