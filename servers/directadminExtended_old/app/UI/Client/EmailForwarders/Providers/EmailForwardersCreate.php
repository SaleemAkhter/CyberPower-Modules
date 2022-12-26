<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\EmailForwarders\Providers;

class EmailForwardersCreate extends EmailForwarders
{

    public function read()
    {
        parent::loadUserApi();

        $this->data['domains']              = [];
        $this->availableValues['domains']   = $this->getDomainList();
    }
}
