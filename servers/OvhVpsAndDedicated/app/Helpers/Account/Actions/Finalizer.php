<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Actions;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\CustomFields;
use ModulesGarden\OvhVpsAndDedicated\App\Libs\Repository\Manage\Machine as MachineManager;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Ovh\Orders;

/**
 * Class Finalizer
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Finalizer
{
    const SERVER_NAME_FIELD = 'serverName|Server Name';

    public static function finalizeCreateAction($serviceID, $serverId)
    {
        CustomFields::set($serviceID, self::SERVER_NAME_FIELD , $serverId);
        MachineManager::createOrUpdateSetting($serverId, MachineManager::REUSE, 'off');
    }


    public static function finalizeCreateVpsAction($serviceID, $orderID)
    {
        $ovhOrders = new Orders();
        $ovhOrders->hosting_id = $serviceID;
        $ovhOrders->order_id = $orderID;
        $ovhOrders->save();
    }
}
