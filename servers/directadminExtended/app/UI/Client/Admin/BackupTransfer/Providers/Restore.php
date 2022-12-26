<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class Restore extends ProviderApi
{

    public function read()
    {
        $this->data['name'] = $this->actionElementId;

        if($this->data['name']){

        }else{
            if($this->getWhmcsParamByKey('producttype')  == "server" )
            {
                $this->loadAdminApi();
                $who=$this->adminApi->backupTransfer->admin_getStep('ip_select');
                $this->data=$who;

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
        $files=array_unique(explode(",",$formData['selectedfiles']));
        // {"where":"local","ftp_ip":"51.83.74.189","ftp_username":"admin_work","ftp_password":"XXXXXXXXXX","ftp_path":"/","ftp_port":"21","ftp_secure":"no","local_path":"/home/admin/admin_backups","ip_choice":"select","ip":"193.31.29.55","reseller_override":"digu087","json":"yes","action":"restore","create_user_home_override":"","encryption_password":"swrewrwerw","select0":"user.resone.goodmail.tar.zst"}:

        $data=[
            'where'=>$formData['where'],
            'ftp_ip'=>$formData['ftp_ip'],
            'ftp_username'=>$formData['ftp_username'],
            'ftp_password'=>$formData['ftp_password'],
            'ftp_path'=>$formData['ftp_path'],
            'ftp_port'=>$formData['ftp_port'],
            'ftp_secure'=>$formData['ftp_secure'],
            'local_path'=>$formData['localpath'],
            'ip_choice'=>$formData['ip_choice'],
            'ip'=>$formData['ip'],
            'reseller_override'=>$formData['reseller_override'],
            'json'=>'yes',
            'action'=>"restore",
            // 'create_user_home_override'=>$formData[''],
            'encryption_password'=>$formData['encryption_password'],

        ];
        foreach ($files as $key => $file) {
            $data['select'.$key]=$file;
        }


        $this->loadAdminApi();
        $response=$this->adminApi->backupTransfer->siteRestore($data);


        if(isset($response->success)){
            return (new ResponseTemplates\RawDataJsonResponse())->setMessage($response->result);
        }else{
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('RestoreCreationFailed');
        }
    }

}
