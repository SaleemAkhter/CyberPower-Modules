<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SysInfo\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class SysInfo extends ProviderApi
{

    public function read()
    {
        if($this->getWhmcsParamByKey('producttype')  == "server" )
        {
            $this->loadAdminApi([],false);
            $data=$this->adminApi->sysInfo->getDetail();
            $this->data=$data;
        }else{
            $this->loadUserApi();
            $result=[];
        }
    }

}
