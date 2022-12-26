<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Settings extends AbstractCommand
{
    const CMD_ADMIN_SETTINGS          = 'CMD_ADMIN_SETTINGS';

    public function all()
    {
        return $this->curl->request(self::CMD_ADMIN_SETTINGS,[],['json'=>'yes','ipp'=>500,'tab'=>'admin']);

        // $settings=[
        //     'admin'=>$this->get(),
        //     'server'=>$this->server(),
        //     'security'=>$this->security(),
        //     'email'=>$this->email(),
        // ];
    }
    public function update($data)
    {
        return $this->curl->request(self::CMD_ADMIN_SETTINGS,$data,['json'=>'yes']);
    }

}
