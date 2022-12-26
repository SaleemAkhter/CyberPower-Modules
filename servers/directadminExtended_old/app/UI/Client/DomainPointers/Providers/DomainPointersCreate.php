<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\DomainPointers\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;

class DomainPointersCreate extends DomainPointers
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;

    public function read()
    {
        $this->loadLang();
        parent::loadUserApi();

        $this->data['domains']              = [];
        $this->availableValues['domains']   = $this->getDomainList();

        $this->data['type']              = [];
        $this->availableValues['type']   = [
            'alias'     => $this->lang->absoluteTranslate('alias'),
            'pointer'   => $this->lang->absoluteTranslate('pointer'),
        ];
    }
}
