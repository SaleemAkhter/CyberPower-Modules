<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SiteRedirection\Providers;


use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;

class SiteRedirectionCreate extends SiteRedirection
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;

    public function read()
    {
        $this->loadLang();
        parent::loadUserApi();

        $this->data['domains']              = [];
        $this->availableValues['domains']   = $this->getDomainList();
        $this->data['protocol'] = [];
        $this->availableValues['protocol'] = [
            'http://' => 'http://',
            'https://' => 'https://'];

        $this->data['type']             = [];
        $this->availableValues['type']  = [
                301 => $this->lang->absoluteTranslate('redirect301'),
                302 => $this->lang->absoluteTranslate('redirect302'),
                303 => $this->lang->absoluteTranslate('redirect303')
            ];
    }

}
