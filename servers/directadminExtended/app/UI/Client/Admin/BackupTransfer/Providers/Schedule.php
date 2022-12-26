<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class Schedule extends ProviderApi
{

    public function read()
    {
        $this->data['name'] = $this->actionElementId;

        if($this->data['name']){

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

    public function create()
    {
       parent::create();
       $formData=$this->formData;
       $selectedUsers=explode(",",$formData['selectedUsers']);
       $data=[
        "who"=>$formData['who'],
        "skip_suspended"=>$formData["skipsuspended"],
        "when"=>$formData["when"],
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
        "ftp_secure"=>$formData["ftp_secure"],
        "append_to_path"=>$formData["append_to_pathoption"],
        "custom_append"=>$formData["custom_append"],
        "what"=>$formData["what"],
        "json"=>"yes",
        "action"=>"create",
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

    foreach ($selectedUsers as $key => $user) {
        $data['select'.$key]=$user;
    }

    $this->loadAdminApi();
    $response=$this->adminApi->backupTransfer->create($data);

    if(isset($response->success)){
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupHasBeenCreated');
    }else{
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupCreationFailed');
    }
    return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupCreationFailed');
}
    public function runnow()
    {
        $data        = ['json'=>'yes','action'=>'select','run'=>'yes'];
        $backups = $this->getRequestValue('massActions', []);
        foreach ($backups as $key=>$id)
        {
            $data['select'.$key] = $id;
        }
        $this->loadAdminApi();
        $response=$this->adminApi->backupTransfer->massAction($data);
        if(isset($response->success)){
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupRunQueued');
        }else{
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupRunFailed');
        }
    }
    public function duplicate()
    {
        $data        = ['json'=>'yes','action'=>'select','duplicate'=>'yes'];
        $backups = $this->getRequestValue('massActions', []);
        foreach ($backups as $key=>$id)
        {
            $data['select'.$key] = $id;
        }
        $this->loadAdminApi();
        $response=$this->adminApi->backupTransfer->massAction($data);
        if(isset($response->success)){
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupCronDuplated');
        }else{
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupCronDuplicateFailed');
        }
    }
    public function deleteMany()
    {
        parent::delete();
        $data        = ['json'=>'yes','action'=>'select','delete'=>'yes'];
        $backups = $this->getRequestValue('massActions', []);
        foreach ($backups as $key=>$id)
        {
            $data['select'.$key] = $id;
        }
        $this->loadAdminApi();
        $response=$this->adminApi->backupTransfer->massAction($data);
        if(isset($response->success)){
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupsCronDeleted');
        }else{
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupCronDeleteFailed');
        }
    }




}
