<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class ApplicationsStaging extends Applications
{
    public function read()
    {
        parent::loadUserApi();

        $this->data['id'] = $this->actionElementId;
        $this->availableValues['domain'] = $this->getDomainAndSubdomainList();
    }

    public function update()
    {
        $this->loadApplicationAPI();
        $this->api->installationStaging($this->formData['id'], $this->formData);
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('applicationHasBeenSetToStaging');
    }
}