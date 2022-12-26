<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Helpers;

use \ModulesGarden\DirectAdminExtended\App\Helper\ApplicationInstaller;

class Applications
{
    private static $installers = [
        1 => ApplicationInstaller::SOFTACULOUS,
        2 => ApplicationInstaller::INSTALLATRON
    ];

    public static function getInstallers()
    {
        return self::$installers;
    }

    public static function getInstallerName($id)
    {
        return self::$installers[$id];
    }

    public static function getAppsArray($installer)
    {
        $out = [];
        switch ($installer)
        {
            case ApplicationInstaller::INSTALLATRON:
                $applications = ApplicationInstaller::getInstallatronApps();
                break;

            case ApplicationInstaller::SOFTACULOUS:
                $applications = ApplicationInstaller::getSoftaculousApps();
                break;

            default:
                return false;
        }

        foreach ($applications as $app)
        {
            $out[$app['name']] = $app['name'];
        }
        asort($out);
        
        return $out;
    }
    
}
