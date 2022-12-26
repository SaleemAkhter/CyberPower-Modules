<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Models\FunctionsSettings;

class ApplicationsCreate extends Applications
{
    public function read()
    {
        parent::loadUserApi();

        $this->data['domain']               = [];
        $this->availableValues['domain']    = $this->getDomainAndSubdomainList();
        $this->availableValues['protocol']  = [
            'http://' => 'http://',
            'http://www.' => 'http://www.',
            'https://' => 'https://',
            'https://www.' => 'https://www.'

        ];
        $this->data['language'] = $this->getLanguage();
    }

    protected function getLanguage()
    {
        $productID = $this->getWhmcsParamByKey('packageid');
        $settings =  FunctionsSettings::factory($productID);

        return ($settings->apps_lang !== "") ? $settings->apps_lang : 'en';
    }
}
