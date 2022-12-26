<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class MailQueue extends AbstractCommand
{
    const CMD_MAIL_QUEUE  = 'CMD_MAIL_QUEUE';

    public function admin_getProcesses()
    {

        $response = $this->curl->request(self::CMD_MAIL_QUEUE, [], [
            'ipp' => 100000000,
            'json'=>'yes',
        ]);
        return $response;
    }



}
