<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SystemBackup\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class File extends ProviderApi
{

    public function read()
    {
        $this->data['name'] = $this->actionElementId;

        if($this->data['name']){

        }else{
            if($this->getWhmcsParamByKey('producttype')  == "server" )
            {
                $this->loadAdminApi();
                $this->data=$this->adminApi->systemBackup->admin_getStep('files');
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
        "file"=>$formData['file'],
        "json"=>"yes",
        "action"=>"addfile"
    ];


    $this->loadAdminApi();
    $response=$this->adminApi->systemBackup->doCommand($data);
    if(isset($response->success)){
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate($response->result);
    }else{
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('FileAdditionFailed');
    }
}
public function deleteMany()
    {
        parent::delete();
        $data        = [
            'action'=>'deletefiles',
            'json'=>'yes',

        ];
        $files = $this->getRequestValue('massActions', []);
        foreach ($files as $key=>$name)
        {
            $data['select'.$key] =$name;
        }
        $this->loadAdminApi();
        $response=$this->adminApi->systemBackup->doCommand($data);

        if(isset($response->success)){
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate($response->result);
        }else{
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate($response->result);
        }
    }



}
