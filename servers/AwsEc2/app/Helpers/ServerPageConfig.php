<?php

namespace ModulesGarden\Servers\AwsEc2\App\Helpers;

use ModulesGarden\Servers\AwsEc2\Core\ModuleConstants;

class ServerPageConfig
{
    public static function getServerPageJs()
    {
        $moduleDir = ModuleConstants::getModuleRootDir();
        $jsFilePath = $moduleDir . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'UI' . DIRECTORY_SEPARATOR
            . 'Admin'. DIRECTORY_SEPARATOR  . 'Templates' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR
            . 'js' . DIRECTORY_SEPARATOR . 'serverPage.js';

        if (!file_exists($jsFilePath) || !is_readable($jsFilePath))
        {
            return '';
        }

        $jsCode = file_get_contents($jsFilePath);

        if (is_string($jsCode))
        {
            return '<script type="application/javascript">' . $jsCode . '</script>';
        }
    }
}
