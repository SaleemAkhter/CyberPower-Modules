<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Providers;


class AddonDomainsDelete extends AddonDomains
{

    public function read()
    {
        $this->data['domain'] = $this->actionElementId;
    }
}
