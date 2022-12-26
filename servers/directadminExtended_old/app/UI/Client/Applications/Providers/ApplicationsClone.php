<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class ApplicationsClone extends Applications
{
    public function read()
    {
        parent::loadUserApi();

        $this->data['id'] = $this->actionElementId;
        $this->availableValues['domain'] = $this->getDomainAndSubdomainList();
    }

    public function create()
    {
        $this->loadApplicationAPI();
        $this->api->installationClone($this->formData['id'], $this->formData);
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('applicationHasBeenCloned');
    }
}