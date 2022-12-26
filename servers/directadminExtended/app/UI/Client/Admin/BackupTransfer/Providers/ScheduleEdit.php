<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class ScheduleEdit extends ProviderApi
{

    public function read()
    {
        $this->data['id'] = $this->actionElementId;
        if($this->data['id']){
            $this->loadAdminApi();
            $who=$this->adminApi->backupTransfer->admin_getBackupDetail($this->data['id']);
            $this->data['who']=$who;
            $where=$this->adminApi->backupTransfer->admin_getStep('where',$this->data['id']);
            $this->data['where']=$where;
        }else{
            if($this->getWhmcsParamByKey('producttype')  == "server" )
            {
                $this->loadAdminApi();
                $who=$this->adminApi->backupTransfer->admin_getStep('who');
                $this->data['who']=$who;

            }else{
                $this->loadUserApi();
                $result=[];
            }
        }

    }

    public function update()
    {
       parent::update();
       $formData=$this->formData;
       $selectedUsers=explode(",",$formData['selectedUsers']);
       $data=[
        'id'=>$this->actionElementId,
        "who"=>$formData['who'],
        "skip_suspended"=>($formData["skipsuspended"]=="off")?"no":$formData["skipsuspended"],
        "when"=>'cron',
        "minute"=>$formData["minute"],
        "hour"=>$formData["hour"],
        "dayofmonth"=>$formData["dayofmonth"],
        "month"=>$formData["month"],
        "dayofweek"=>$formData["dayofweek"],
        "where"=>$formData["where"],
        "ftp_ip"=>$formData["ftp_ip"],
        "ftp_username"=>$formData["ftp_username"],
        "ftp_password"=>$formData["ftp_password"],
        "ftp_path"=>$formData["ftp_path"],
        "ftp_port"=>$formData["ftp_port"],
        "ftp_secure"=>($formData["ftp_secure"]=="off")?"no":$formData["ftp_secure"],
        "append_to_path"=>$formData["append_to_pathoption"],
        "custom_append"=>$formData["custom_append"],
        "what"=>$formData["what"],
        "json"=>"yes",
        "action"=>"modify",
        "form_version"=>4,
        "local_path"=>$formData["localpath"],
        "encryption_password"=>"",
        "option0"=>$formData["option0"],
        "option1"=>$formData["option1"],
        "option2"=>$formData["option2"],
        "option3"=>$formData["option3"],
        "option4"=>$formData["option4"],
        "option5"=>$formData["option5"],
        "option6"=>$formData["option6"],
        "option7"=>$formData["option7"],
        "option8"=>$formData["option8"],
        "option9"=>$formData["option9"],
        "option10"=>$formData["option10"],
        "option11"=>$formData["option11"],
        "option12"=>$formData["option12"],
        "option13"=>$formData["option13"],
    ];
    for ($i=0; $i <=13 ; $i++) {
        if($data['option'.$i]=="off"){
           unset($data['option'.$i]) ;
        }
    }
    foreach ($selectedUsers as $key => $user) {
        $data['select'.$key]=$user;
    }
    // debug($data);
    $this->loadAdminApi();
    $response=$this->adminApi->backupTransfer->update($data);
    // debug($response);die();
    if(isset($response->success)){
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupHasBeenCreated');
    }else{
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupCreationFailed');
    }
    return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupCreationFailed');
}
}
