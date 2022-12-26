<?php


namespace ModulesGarden\Servers\AwsEc2\App\Config\Packages;

use ModulesGarden\Servers\AwsEc2\Core\App\Packages\AppPackageConfiguration;

class Provisioning extends AppPackageConfiguration
{
    const APP_CONFIGURATION =
    [
        self::PACKAGE_STATUS => self::PACKAGE_STATUS_ACTIVE
    ];
}
