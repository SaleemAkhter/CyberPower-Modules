<?php


namespace ModulesGarden\Servers\VultrVps\App\Config\Packages;

use ModulesGarden\Servers\VultrVps\Core\App\Packages\AppPackageConfiguration;

class Provisioning extends AppPackageConfiguration
{
    const APP_CONFIGURATION =
        [
            self::PACKAGE_STATUS => self::PACKAGE_STATUS_ACTIVE
        ];
}
