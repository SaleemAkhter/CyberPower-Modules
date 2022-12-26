<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class ApplicationsPushToLive extends Applications
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
        $this->api->installationPushToLive($this->formData['id']);
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('applicationHasBeenPushedToLive');
    }
}