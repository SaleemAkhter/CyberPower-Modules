<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SystemBackup\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class Directory extends ProviderApi
{

    public function read()
    {
        $this->data['name'] = $this->actionElementId;

        if($this->data['name']){

        }else{
            if($this->getWhmcsParamByKey('producttype')  == "server" )
            {
                $this->loadAdminApi();
                $this->data=$this->adminApi->systemBackup->admin_getStep('directories');
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
        "directory"=>$formData['directory'],
        "json"=>"yes",
        "action"=>"adddirectory"
    ];


    $this->loadAdminApi();
    $response=$this->adminApi->systemBackup->doCommand($data);
    if(isset($response->success)){
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('DirectoryAddedSuccessfully');
    }else{
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('DirectoryAdditionFailed');
    }
}
public function deleteMany()
    {
        parent::delete();

        $data        = [
            'action'=>'deletedirs',
            'json'=>'yes',

        ];
        $directories = $this->getRequestValue('massActions', []);
        foreach ($directories as $key=>$name)
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
