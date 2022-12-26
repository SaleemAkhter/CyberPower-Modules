<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\UserDomainComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

class ApplicationProviderApi extends BaseDataProvider
{
    use DirectAdminAPIComponent;
    use UserDomainComponent;

    public function read()
    {

    }

    public function create()
    {

    }

    public function delete()
    {

    }

    public function update()
    {

    }

    public function loadApplicationAPI()
    {
        $this->api = (new ApplicationInstaller($this->loadRequiredParams()))->getInstaller();

        return $this;
    }


}
