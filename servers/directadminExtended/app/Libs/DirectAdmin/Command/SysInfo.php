<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class SysInfo extends AbstractCommand
{
    const CMD_SYSTEM_INFO  = 'CMD_SYSTEM_INFO';


    public function getDetail()
    {

        $response = $this->curl->request(self::CMD_SYSTEM_INFO, [], [
            'ipp' => 100000000,
            'json'=>'yes',
            'bytes'=>'yes'
        ]);
        return $response;
    }

}
