<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 16:37
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Interfaces\ResponseLoad;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\AbstractModel;

class Logs extends AbstractModel implements ResponseLoad
{

    protected $logs = [];

    public function loadResponse($response, $function = null)
    {

        $lines =  explode(PHP_EOL, $response);

        foreach($lines as $line)
        {
            if(empty($line))
            {
                continue;
            }

            $this->addLogs($line);
        }

        return $this;

    }

    /**
     * @return array
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @param string $logs
     */
    public function addLogs($log)
    {
        $this->logs[] = $log;
    }




}