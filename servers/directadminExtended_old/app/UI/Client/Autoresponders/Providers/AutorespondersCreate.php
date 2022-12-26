<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Autoresponders\Providers;

class AutorespondersCreate extends Autoresponders
{

    public function read()
    {
        parent::read();

        $this->data['domain']                = [];
        $this->availableValues['domain']     = $this->getDomainList();
    }
}
