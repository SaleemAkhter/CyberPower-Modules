<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Subdomains\Providers;

class SubdomainsCreate extends Subdomains
{

    public function read()
    {
        parent::loadUserApi();

        $this->data['domains']              = [];
        $this->availableValues['domains']   = $this->getDomainList();
    }
}
