<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Helpers;

/**
 * Description of Logger
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Logger
{

    public static function addLogs($productID = null, $action, $request = null, $response = null)
    {

        if (self::getDebugStatus($productID))
        {
            $request  = (is_object($request)) ? json_encode($request) : $request;
            $response = (is_object($response)) ? json_encode($response) : $response;
            logModuleCall('DigitalOceanDroplets', $action, $request, $response);
        }
    }

    private static function getDebugStatus($productID = null)
    {
        if (is_null($productID))
        {
            return true;
        }
        $fields = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\FieldsProvider($productID);
        return ($fields->getField('debugMode') == "on") ? true : false;
    }

}
