<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\DataTable\DataProviders\Providers;

/**
 *
 */
class JsonDataProvider extends \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider
{

    public function setData($data)
    {
        $this->data = json_decode($data);
        
        return $this;
    }
}
