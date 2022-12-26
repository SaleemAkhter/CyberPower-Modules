<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ErrorPages\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class ErrorPages extends ProviderApi
{

    public function read()
    {
        if ($this->getRequestValue('index') === 'editForm')
        {
            return;
        }
        parent::read();

        $this->data['errorPage']        = $this->actionElementId;
        $this->data['errorPageHidden']  = $this->actionElementId;

        $data = [
            'path'  => '/domains/' . $this->getWhmcsParamByKey('domain') . '/public_html/' . $this->actionElementId
        ];

        // IF File does not exist at all
        try
        {
            $response = $this->userApi->fileManager->view(new Models\Command\FileManager($data))->first();
        }
        catch (\Exception $ex)
        {
            $this->data['body'] = '';

            return;
        }
        $this->data['body'] = $response->getText();
    }

    public function update()
    {
        parent::update();

        $data = [
            'name'  => $this->formData['errorPageHidden'],
            'text'  => html_entity_decode($this->formData['body']),
            'path'  => '/domains/' . $this->getWhmcsParamByKey('domain') . '/public_html'
        ];
        $this->userApi->fileManager->edit(new Models\Command\FileManager($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('pageHasBeenUpdated');
    }

}
