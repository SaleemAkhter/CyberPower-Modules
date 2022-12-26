<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Service\CronTask;

use Exception;

class RegisterTask
{
    public static function assignVM($serverID, $projectID, $dropletID)
    {
        $params = [
            'serverID'  => $serverID,
            'projectID' => $projectID,
            'dropletID' => $dropletID
        ];

        self::addNew('assignVM', $params);
    }

    public static function assignFloatingIp($serverID, $dropletID)
    {
        $params = [
            'serverID'  => $serverID,
            'dropletID' => $dropletID
        ];
        self::addNew('assignFloatingIp', $params);
    }

    public static function assignFloatingIpToDropletId($serverID, $dropletID, $ip = null, $createNew = false)
    {
        $params = [
            'serverID'  => $serverID,
            'dropletID' => $dropletID,
            'ip'        => $ip,
            'createNew' => $createNew
        ];
        self::addNew('assignFloatingIpToDropletId', $params);
    }

    public static function unassignFloatingIpToDropletId($serverID, $ip = null)
    {
        $params = [
            'serverID' => $serverID,
            'ip'       => $ip,
        ];
        self::addNew('unassignFloatingIpToDropletId', $params);
    }

    public static function deleteVolume($serverID, $volumeID)
    {
        $params = [
            'serverID' => $serverID,
            'id'       => $volumeID
        ];

        self::addNew('deleteVolume', $params);
    }

    public static function powerOn($serverID, $dropletID, $actionId)
    {
        $params = [
            'serverID'  => $serverID,
            'dropletID' => $dropletID,
            'actionID'  => $actionId
        ];
        self::addNew('powerOn', $params);
    }

    private static function addNew($action, $params = [])
    {
        try
        {
            $task         = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Models\CronTasks();
            $task->action = $action;
            $task->params = json_encode($params);
            $task->status = 0;
            $task->save();
        }
        catch (Exception $ex)
        {

        }
    }

}
