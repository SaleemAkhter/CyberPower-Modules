<?php


namespace ModulesGarden\Servers\HetznerVps\App\Config\Packages;

use ModulesGarden\Servers\HetznerVps\Core\App\Packages\AppPackageConfiguration;

class Provisioning extends AppPackageConfiguration
{
    const APP_CONFIGURATION =
        [
            self::PACKAGE_STATUS => self::PACKAGE_STATUS_ACTIVE
        ];
}
