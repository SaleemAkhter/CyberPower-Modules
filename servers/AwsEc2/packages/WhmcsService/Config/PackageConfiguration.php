<?php

namespace ModulesGarden\Servers\AwsEc2\Packages\WhmcsService\Config;

use ModulesGarden\Servers\AwsEc2\Core\App\Packages\BasePackageConfiguration;
use ModulesGarden\Servers\AwsEc2\Core\App\Packages\PackageConfigurationConst;

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
