<?php

namespace ModulesGarden\Servers\VultrVps\App\Api;

use ModulesGarden\Servers\VultrVps\App\Api\Models\Instance;
use ModulesGarden\Servers\VultrVps\App\Enum\CustomField;
use ModulesGarden\Servers\VultrVps\App\Helpers\HostingCustomField;
use ModulesGarden\Servers\VultrVps\App\Helpers\ProductCustomFields;
use ModulesGarden\Servers\VultrVps\Core\Models\Whmcs\Hosting;
use ModulesGarden\Servers\VultrVps\Core\UI\Traits\WhmcsParams;

class FirewallGroupFactory
{
    use WhmcsParams;

    /**
     * @return Models\FirewallGroup
     */
    public function fromWhmcsParams()
    {
        if (!$this->getWhmcsCustomField(CustomField::FIREWALL_GROUP_ID))
        {
            throw new \InvalidArgumentException("Custom field Firewall Group ID is empty");
        }
        $api = (new ApiClientFactory())->fromWhmcsParams();
        return $api->firewallGroup($this->getWhmcsCustomField(CustomField::FIREWALL_GROUP_ID));
    }

    /**
     * @param Hosting $hosting
     * @return Instance
     */
    public static function fromHosting(Hosting $hosting){

        $customField = new HostingCustomField($hosting);
        $instanceId = $customField->get(CustomField::INSTANCE_ID);
        if (!$instanceId)
        {
            throw new \InvalidArgumentException("Custom field Instance ID is empty");
        }
        return (new Instance())->setId($instanceId);

    }
}