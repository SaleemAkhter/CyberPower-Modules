<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Enum\CustomField;
use function ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\sl;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\WhmcsParams;

class InstanceFactory
{
    use WhmcsParams;

    /**
     * @return \Google_Service_Compute_Instance
     */
    public function fromParams(){
        if (!$this->getWhmcsCustomField(CustomField::INSTANCE_ID))
        {
            throw new \InvalidArgumentException("Custom field Instance ID is empty");
        }
        if (!$this->getWhmcsCustomField(CustomField::ZONE))
        {
            throw new \InvalidArgumentException("Custom field Zone is empty");
        }
        $instance = new \Google_Service_Compute_Instance();
        $instance->setId($this->getWhmcsCustomField(CustomField::INSTANCE_ID));
        $instance->setZone($this->getWhmcsCustomField(CustomField::ZONE));
        return $instance;
    }


}