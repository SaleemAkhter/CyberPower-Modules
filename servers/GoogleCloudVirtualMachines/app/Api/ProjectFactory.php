<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\WhmcsParams;

class ProjectFactory
{
    use WhmcsParams;

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function fromParams(){
        if (!$this->getWhmcsParamByKey('serverusername'))
        {
            throw new \InvalidArgumentException("Server Username is empty.");
        }
        return $this->getWhmcsParamByKey('serverusername');
    }

}