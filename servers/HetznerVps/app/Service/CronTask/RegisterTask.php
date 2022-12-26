<?php

namespace ModulesGarden\Servers\HetznerVps\App\Service\CronTask;

use Exception;

class RegisterTask
{
    public static function deleteVolume($serverID, $volumeID)
    {
        $params = [
            'serverID' => $serverID,
            'id' => $volumeID
        ];

        self::addNew('deleteVolume', $params);
    }

    public static function attachVolume($serverID, $serviceID, $volumeID)
    {
        $params = [
            'serverID' => $serverID,
            'serviceID' => $serviceID,
            'id' => $volumeID
        ];

        self::addNew('attachVolume', $params);
    }

    public static function deleteFloatingIP($ipsToDelete, $params)
    {
        $params['ipsToDelete'] = $ipsToDelete;
        self::addNew('deleteFloatingIP', $params);
    }

    public static function createFloatingIP($serverID, $params)
    {
        $params['createData'] = [
            'type' => "ipv4",
            'serverID' => $serverID
        ];

        self::addNew('createFloatingIP', $params);
    }

    public static function enableBackups($serverID, $params){
        $params['serverID'] = $serverID;
        self::addNew('enableBackups', $params);
    }
    public static function disableBackups($serverID, $params){
        $params['serverID'] = $serverID;
        self::addNew('disableBackups', $params);
    }

    private static function addNew($action, $params = [])
    {
        try {
            $task = new \ModulesGarden\Servers\HetznerVps\App\Models\CronTasks();
            $task->action = $action;
            $task->params = json_encode($params);
            $task->status = 0;
            $task->save();
        } catch (Exception $ex) {

        }
    }

}
