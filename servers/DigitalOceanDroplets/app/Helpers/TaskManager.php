<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Models\TaskHistory;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;

/**
 * Description of ConfigOptions
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 * 
 */
class TaskManager
{

    public static function addNew($hostingID, $taskName, $additionalVaribles = [])
    {
        $task             = new TaskHistory();
        $task->hosting_id = $hostingID;
        $task->task       = self::getTaskName($taskName, $additionalVaribles);
        $task->save();
    }

    private static function getTaskName($task, $additionalVaribles = [])
    {
        if (empty($additionalVaribles))
        {
            return Lang::getInstance()->absoluteT('task', $task);
        }
        return sprintf(Lang::getInstance()->absoluteT('task', $task), ...$additionalVaribles);
    }

}
