<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\PluginManager\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;


class PluginUpdate extends Plugin
{

    public function read()
    {
        $this->data['plugin'] = $this->actionElementId;
    }

    public function update()
    {
        parent::update();
        $formData=$this->formData;
        $data = [
            'passwd'       => $formData['password'],
            'json'         => 'yes',
            'action'=>'select',
            'update'=>'yes',
            'select0'=>$formData['plugin'],
        ];

        $this->loadAdminApi();
        $response=$this->adminApi->pluginManager->action($data);

        if(isset($response->success)){
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate($response->success);
        }else{
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate($response->result);
        }
    }
}
