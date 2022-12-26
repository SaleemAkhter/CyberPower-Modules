<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;

class SslCreate extends Ssl
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
    public function read()
    {
        $this->loadLang();
        parent::loadUserApi();

        $this->data['domains']              = [];
        $this->availableValues['domains']   = $this->getDomainList();

        $this->availableValues['size']   = [
            '2048'  => $this->lang->absoluteTranslate('2048bits'),
            '4096'  => $this->lang->absoluteTranslate('4096bits'),
        ];
        $this->data['size']              = '4096';
    }
}
