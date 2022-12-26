<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Models;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\Product as ProductModel;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\ServersRelations;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\ServerSettingsManage;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\Constants as ServerSettingsManageConstants;
/**
 * Class Product
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Product
{
    public static function getServerTypeById($productId)
    {
        $serverGroupId = ProductModel::where('id', $productId)->first()->servergroup;

        $server        = ServersRelations::where('groupid', $serverGroupId)->get()->first();
        $serverType    = ServerSettingsManage::getValueIfSetting($server->serverid, ServerSettingsManageConstants::OVH_SERVER_TYPE);

        return $serverType;
    }
}