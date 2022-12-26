<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class SystemInfo extends AbstractCommand
{
    const CMD_SSL           = 'CMD_API_SYSTEM_INFO';


    public function lists()
    {
        $response = $this->curl->request(self::CMD_SSL);

        return $this->loadResponse(new Models\Command\SystemInfo(), $response);
    }


}