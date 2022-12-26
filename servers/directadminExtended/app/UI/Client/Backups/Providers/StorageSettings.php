<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\Plesk\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;

class StorageSettings extends ProviderApi
{

    public function read()
    {
        parent::loadPleskApi();
        
        $data = [
            'webspaceId' => $this->webspaceId
        ];
        $result = $this->plesk->apiXML->backup->getRemoteStorageSettings(new Models\ApiXML\Backup($data))
                ->get
                ->first();
        
        if($result)
        {
            $this->data['host']         = $result->getHost();
            $this->data['directory']    = $result->getDirectory() != 'false' ? $result->getDirectory() : '';
            $this->data['username']     = $result->getLogin();
            $this->data['password']     = $result->getPassword();
            $this->data['passiveMode']  = $result->getPassiveMode() == 'true' ? 'on' : 'off';
        }
    }

    public function update()
    {
        parent::loadPleskApi();
        
        $data = [
            'webspaceId'    => $this->webspaceId,
            'host'          => $this->formData['host'],
            'directory'     => $this->formData['directory'],
            'login'         => $this->formData['username'],
            'password'      => $this->formData['password'],
            'passiveMode'   => $this->formData['passiveMode'] == 'on' ? 'true' : 'false'
        ];
        $this->plesk->apiXML->backup->setRemoteStorageSettings(new Models\ApiXML\Backup($data));
        
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('storageSettingsHaveBeenSaved');
    }
    
}
