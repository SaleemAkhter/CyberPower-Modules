<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class Backup extends ProviderApi
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
        $selectedSettings=array_unique(explode(",",$formData['selectedSettings']));
        if($formData['domains']=='all'){
            $this->loadUserApi();
            $selectedDomains=[];
            $result     = $this->userApi->domain->lists()->response;
            foreach($result as $items){
                $selectedDomains[]= $items->name;
            }
        }else{
            $selectedDomains=array_unique(explode(",",$formData['selectedDomains']));
        }

        if(!empty($selectedDomains)){
            foreach ($selectedDomains as $key => $domain) {
                $data=[
                    "domain"=>$domain,
                    "json"=>"yes",
                    "action"=>"backup",
                    "form_version"=>"4",
                ];
                foreach ($selectedSettings as $key => $setting) {
                    $data['select'.$key]=$setting;
                }

                $this->loadAdminApi();
                $response=$this->adminApi->backupTransfer->siteBackup($data);
            }
            if(isset($response->success)){
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupHasBeenCreated');
            }else{
                return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupCreationFailed');
            }
        }
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupCreationFailed');
    }





}
