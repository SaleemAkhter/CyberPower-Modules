<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class HttpdConfig extends AbstractCommand
{
    const CMD_CUSTOM_HTTPD          = 'CMD_CUSTOM_HTTPD';

    public function get()
    {
        return $this->curl->request(self::CMD_CUSTOM_HTTPD,[],['json'=>'yes','ipp'=>500]);


    }
    public function gethttpdconffile($domain)
    {
        return $this->curl->request(self::CMD_CUSTOM_HTTPD,[],['json'=>'yes','domain'=>$domain,'ipp'=>500,'proxy'=>'no']);


    }


    // public function update($data)
    // {
    //     return $this->curl->request(self::CMD_CUSTOM_HTTPD,$data,['json'=>'yes']);
    // }

}
