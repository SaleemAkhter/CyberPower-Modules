<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\UserDomainComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

class ProviderApi extends BaseDataProvider
{

    use DirectAdminAPIComponent;
    use UserDomainComponent;

    private $productConfiguration = null;

    public function read()
    {
        $this->loadUserApi();
    }

    public function create()
    {
        $this->loadUserApi();
    }

    public function delete()
    {
        $this->loadUserApi();
    }

    public function update()
    {
        $this->loadUserApi();
    }

    public function suspendUnsuspend()
    {
        $this->loadUserApi();
    }

    public function put()
    {
        $this->loadUserApi();
    }

    public function fileManager()
    {
        $this->loadFileManager();
    }

    protected function getProductConfiguration($config)
    {
        if(is_null($this->productConfiguration))
        {
            $this->loadConfiguration();
        }
        return $this->productConfiguration[$config];
    }
    private function loadConfiguration()
    {
        $repository =  new Repository();
        $this->productConfiguration = $repository->getProductSettings($this->getWhmcsParamByKey('packageid'));
    }

}
