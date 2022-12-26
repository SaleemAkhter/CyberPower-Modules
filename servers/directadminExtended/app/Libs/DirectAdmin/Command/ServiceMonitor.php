<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class ServiceMonitor extends AbstractCommand
{
    const CMD_SHOW_SERVICES  = 'CMD_SHOW_SERVICES';
    const CMD_SERVICE        = 'CMD_SERVICE';
    public function admin_getServices()
    {

        $response = $this->curl->request(self::CMD_SHOW_SERVICES, [], [
            'ipp' => 100000000,
            'json'=>'yes',
            'bytes'=>'yes'
        ]);
        return $response;
    }

    public function doCommand($data)
    {

        $response = $this->curl->request(self::CMD_SERVICE, $data, [
            'json'=>'yes',
        ]);
        return $response;
    }



}
