<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\ServersRelations;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\Product;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Basics;
use WHMCS\Module\Server as WhmcsServer;

/**
 * Class Details
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Details
{

    public static function getServerDetails($productId)
    {
        $serverGroupID = Product::where('id', $productId)->first()->servergroup;
        $server = ServersRelations::where('groupid', $serverGroupID)->with('servers')->first();

        return [
            'serverid'         => $server->serverid,
            'serverpassword'   => \decrypt($server->servers->password),
            'serverusername'   => $server->servers->username,
            'serveraccesshash' => $server->servers->accesshash,
        ];
    }


    public static function getDataToSaveFromRequest()
    {
        $wanted      = [
            Constants::ENDPOINT,
            Constants::OVH_SUBSIDIARY,
            Constants::OVH_SERVER_TYPE,
            Basics\BaseConstants::ID
        ];
        $replaceKeys = self::getReplaceKeys();
        $out         = [];

        foreach ($_REQUEST as $key => $value)
        {
            if (in_array($key, $wanted))
            {
                $key       = isset($replaceKeys[$key]) ? $replaceKeys[$key] : $key;
                $out[$key] = $value;
            }
        }
        return $out;
    }

    public static function getReplaceKeys()
    {
        return [
            Basics\BaseConstants::ID => Constants::SERVER_ID
        ];
    }

    public static function getOvhServerTypeByServiceId($serviceId)
    {
        $server = new WhmcsServer();
        $server->setServiceId($serviceId);
        $result = $server->buildParams();

        $serverType = ServerSettingsManage::getValueIfSetting($result['serverid'], Constants::OVH_SERVER_TYPE);

        return $serverType;
    }
}