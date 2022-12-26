<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SystemBackup\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class CronSchedule extends ProviderApi
{

    public function read()
    {
        $this->data['name'] = $this->actionElementId;

        if($this->data['name']){

        }else{
            if($this->getWhmcsParamByKey('producttype')  == "server" )
            {
                $this->loadAdminApi();
                $data=$this->adminApi->systemBackup->admin_getStep('basic');
                $this->data=json_decode(json_encode($data->cron),true);
                if($this->data['CRON']=="checked"){
                    $this->data['CRON']="on";
                }else{
                     $this->data['CRON']="off";
                }
            }else{
                $this->loadUserApi();
                $result=[];
            }
        }

    }

    public function create()
    {
       parent::create();
       $formData=$this->formData;
       $data=[
            "use_cron"=>($formData['CRON']=="on")?"yes":"no",
            "minute"=>$formData["MINUTE"],
            "hour"=>$formData["HOUR"],
            "dayofmonth"=>$formData["DAY"],
            "month"=>$formData["MONTH"],
            "dayofweek"=>$formData["DAYOFWEEK"],
            "json"=>"yes",
            "action"=>"when"
        ];

    $this->loadAdminApi();
    $response=$this->adminApi->systemBackup->doCommand($data);
    if(isset($response->success)){
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('UpdatedRootCron');
    }else{
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('RootCronUpdateFailed');
    }
}





}
