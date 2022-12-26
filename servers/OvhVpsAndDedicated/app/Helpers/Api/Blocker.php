<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Api;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\FileReader\Reader;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ModuleConstants;

/**
 * Class Blocker
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Blocker
{
    CONST BLOCKED_SERVICES_FILE = 'blockedServices.json';

    CONST BLOCKED_SERVICES_KEY = 'blockedServices';
    
    CONST BLOCKED_IMAGES_FILE = 'blockedImages.json';

    CONST BLOCKED_IMAGES_KEY = 'blockedImages';

    public static function getBlockedServices()
    {
        $path = ModuleConstants::getDevConfigDir() . DS . SELF::BLOCKED_SERVICES_FILE;

        return Reader::read($path)->get(SELF::BLOCKED_SERVICES_KEY);
    }

    public static function isServiceBlocked($serviceName, array $services = [])
    {
        if(empty($services))
        {
            $services = self::getBlockedServices();
        }

        return in_array($serviceName, $services);
    }
    
    public static function getBlockedImages()
    {
        $path = ModuleConstants::getDevConfigDir() . DS . SELF::BLOCKED_IMAGES_FILE;

        return Reader::read($path)->get(SELF::BLOCKED_IMAGES_KEY);
    }
}