<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Packages\Providers;


class PackagesDelete extends Packages
{

    public function read()
    {
        $this->data['package'] = $this->actionElementId;
    }
}
