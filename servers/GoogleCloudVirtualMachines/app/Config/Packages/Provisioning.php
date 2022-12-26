<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Config\Packages;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Packages\AppPackageConfiguration;

class Provisioning extends AppPackageConfiguration
{
    const APP_CONFIGURATION =
    [
        self::PACKAGE_STATUS => self::PACKAGE_STATUS_ACTIVE
    ];
}
