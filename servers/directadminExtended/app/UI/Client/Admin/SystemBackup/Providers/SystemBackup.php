<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SystemBackup\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class SystemBackup extends ProviderApi
{

    public function read()
    {
        $this->data['name'] = $this->actionElementId;

        if($this->data['name']){

        }else{
            if($this->getWhmcsParamByKey('producttype')  == "server" )
            {
                $this->loadAdminApi();
                $this->data=$this->adminApi->systemBackup->admin_getStep('basic');
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
       $data=[
        "BACKUP_PATH"=>$formData['BACKUP_PATH'],
        "MIN_DISK"=>$formData["MIN_DISK"],
        "MOUNT_POINT"=>$formData["SELECTED_MOUNT_POINT"],
        "HTTP_BK"=>($formData["HTTP_BK"]=="on")?"yes":"no",
        "BIND_BK"=>($formData["BIND_BK"]=="on")?"yes":"no",
        "MYSQL_BK"=>($formData["MYSQL_BK"]=="on")?"yes":"no",
        "CUSTOM_BK"=>($formData["CUSTOM_BK"]=="on")?"yes":"no",
        "ADD_USERS_TO_LIST"=>($formData["ADD_USERS_TO_LIST"]=="on")?"yes":"no",
        "USE_RTRANS"=>($formData["USE_RTRANS"]=="on")?"yes":"no",
        "RTRANS_METHOD"=>$formData["RTRANS_METHOD"],
        "DEL_AFTERTRANS"=>($formData["DEL_AFTERTRANS"]=="on")?"yes":"no",
        "FBF_RTRANS"=>($formData["FBF_RTRANS"]=="on")?"yes":"no",
        "FTP_HOST"=>$formData["FTP_HOST"],
        "FTP_USER"=>$formData["FTP_USER"],
        "FTP_PASS"=>$formData["FTP_PASS"],
        "FTP_RPATH"=>$formData["FTP_RPATH"],
        "json"=>"yes",
        "action"=>"setting"
    ];


    $this->loadAdminApi();
    $response=$this->adminApi->systemBackup->doCommand($data);
    if(isset($response->success)){
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupSettingsSaved');
    }else{
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupSettingsSaveFailed');
    }
    return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupSettingsSaveFailed');
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

    public function runBackup()
    {
       parent::create();
       $data=[
            "json"=>"yes",
            "action"=>"now"
        ];

        $this->loadAdminApi();
        $response=$this->adminApi->systemBackup->doCommand($data);
        if(isset($response->success)){
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupRunSuccessfull');
        }else{
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupRunFailed');
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
