<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamFilter\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;

class SpamFilterCreate extends SpamFilter
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
    public function read()
    {
        $this->loadLang();
        parent::loadUserApi();

        $this->data['domains']              = [];
        $this->availableValues['domains']   = $this->getDomainList();

        $this->data['filter']               = [];
        $this->availableValues['filter']    = [
            'email'     => $this->lang->absoluteTranslate('emailFilter'),
            'domain'    => $this->lang->absoluteTranslate('domainFilter'),
            'word'      => $this->lang->absoluteTranslate('wordFilter'),
            'size'      => $this->lang->absoluteTranslate('sizeFilter')
        ];
    }

}
