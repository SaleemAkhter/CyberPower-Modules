<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class ProcessMonitor extends AbstractCommand
{
    const CMD_PROCESS_MONITOR  = 'CMD_PROCESS_MONITOR';

    public function admin_getProcesses()
    {

        $response = $this->curl->request(self::CMD_PROCESS_MONITOR, [], [
            'ipp' => 100000000,
            'json'=>'yes',
        ]);
        return $response;
    }



}
