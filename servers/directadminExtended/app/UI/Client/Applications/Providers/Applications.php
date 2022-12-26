<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ApplicationProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Installation;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;

class Applications extends ApplicationProviderApi
{
    public function read()
    {
        $this->data['id'] = $this->actionElementId;
    }

    public function create()
    {

        $this->loadApplicationAPI();
        $model                         = $this->api->getInstallationFields($this->formData['sid']);
        $this->formData['application'] = $this->formData['sid'];

        $model->fillFieldsData($this->formData);
        $this->api->installationCreate($this->formData['sid'], $model);

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('applicationHasBeenInstalled')->setCallBackFunction('redirectAfterInstallApp');
    }

    public function delete()
    {
        $data = [
            'removeDir'     => empty($this->formData['remove_dir']) ? 0 : 1,
            'removeDb'      => empty($this->formData['remove_db']) ? 0 : 1,
            'removeDatadir' => empty($this->formData['remove_datadir']) ? 0 : 1,
            'removeWwwdir'  => empty($this->formData['remove_wwwdir']) ? 0 : 1,
            'removeins'     => 1
        ];
        $this->loadApplicationAPI();
        $this->api->installationDelete($this->formData['id'], new Installation($data));
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('applicationHasBeenDeleted');
    }

    public function update()
    {
        
    }
}
