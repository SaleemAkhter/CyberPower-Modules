<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ServiceMonitor\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class ServiceMonitor extends ProviderApi
{

    public function read()
    {
        $this->data['name'] = $this->actionElementId;

        if($this->data['name']){

        }else{
            if($this->getWhmcsParamByKey('producttype')  == "server" )
            {
                $this->loadAdminApi([],false);
                $this->data=$this->adminApi->serviceMonitor->admin_getServices();
            }else{
                $this->loadUserApi();
                $result=[];
            }
        }

    }


    public function start()
    {
        $this->loadAdminApi();
        $service = $this->actionElementId;

        $postdata = [
            'service' => $service,
            'json'=>'yes',
            'action'=>'start'
        ];
        return $this->adminApi->serviceMonitor->doCommand($postdata);
    }
    public function stop()
    {
        $this->loadAdminApi();
        $service = $this->actionElementId;

        $postdata = [
            'service' => $service,
            'json'=>'yes',
            'action'=>'stop'
        ];
        return $this->adminApi->serviceMonitor->doCommand($postdata);
    }
    public function restart()
    {
        $this->loadAdminApi();
        $service = $this->actionElementId;

        $postdata = [
            'service' => $service,
            'json'=>'yes',
            'action'=>'restart'
        ];
        return $this->adminApi->serviceMonitor->doCommand($postdata);
    }
    public function reload()
    {
        $this->loadAdminApi();
        $service = $this->actionElementId;

        $postdata = [
            'service' => $service,
            'json'=>'yes',
            'action'=>'reload'
        ];
        return $this->adminApi->serviceMonitor->doCommand($postdata);
    }



}
