<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ResellerPackages\Providers;


class PackagesDelete extends Packages
{

    public function read()
    {
        $this->data['package'] = $this->actionElementId;
    }
}
