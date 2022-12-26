<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class SystemBackup extends AbstractCommand
{
    const CMD_SYSTEM_BACKUP  = 'CMD_SYSTEM_BACKUP';
    const CMD_SERVICE        = 'CMD_SERVICE';
    public function admin_getStep($tab='basic')
    {

        $response = $this->curl->request(self::CMD_SYSTEM_BACKUP, [], [
            'ipp' => 100000000,
            'json'=>'yes',
            'tab'=>$tab
        ]);
        return $response;
    }

    public function doCommand($data)
    {
        $response = $this->curl->request(self::CMD_SYSTEM_BACKUP, $data, [
            'json'=>'yes',
        ]);
        return $response;
    }



}
