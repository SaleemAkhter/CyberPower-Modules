<?php

namespace ModulesGarden\Servers\VultrVps\App\Http\Actions;

use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\App\Api\Models\Instance;
use ModulesGarden\Servers\VultrVps\App\Enum\CustomField;
use ModulesGarden\Servers\VultrVps\App\Events\VmDeleted;
use ModulesGarden\Servers\VultrVps\App\Traits\ApiClient;
use ModulesGarden\Servers\VultrVps\App\Traits\CustomFieldUpdate;
use ModulesGarden\Servers\VultrVps\App\Traits\ProductSetting;
use ModulesGarden\Servers\VultrVps\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\Servers\VultrVps\Core\UI\Traits\WhmcsParams;


class TerminateAccount extends AddonController
{
    use WhmcsParams, ProductSetting, ApiClient, CustomFieldUpdate;

    /**
     * @var Instance
     */
    protected $instance;

    public function execute($params = null)
    {
        try
        {
            if(!$this->getWhmcsParamByKey('customfields')[CustomField::INSTANCE_ID]){
                throw new \InvalidArgumentException("Custom Field Instance ID is empty");
            }
            $this->instanceDelete();
            //firewall group
            if($this->getWhmcsParamByKey('customfields')[CustomField::FIREWALL_GROUP_ID]){
                $this->apiClient()->firewallGroup($this->getWhmcsParamByKey('customfields')[CustomField::FIREWALL_GROUP_ID])
                                 ->delete();
                $this->customFieldUpdate(CustomField::FIREWALL_GROUP_ID, "");
            }
            //backup
            $this->backupsDelete();
            //snapshots
            $this->instance
                 ->snapshots()
                 ->delete();
            return 'success';
        }
        catch (\Exception $exc)
        {
            return $exc->getMessage();
        }
    }

    private function instanceDelete(){
        $this->instance = $this->apiClient()->instance($this->getWhmcsParamByKey('customfields')[CustomField::INSTANCE_ID]);
        if ($this->instance->isRunning())
        {
            $this->instance->halt();
            sleep(1);
        }
        $response = $this->instance->delete();
        $this->customFieldUpdate(CustomField::INSTANCE_ID, "");
        sleep(15);
    }

    private function backupsDelete(){
        if(!$this->getWhmcsParamByKey('model')->dedicatedip){
            return;
        }
        $this->instance
            ->backups()
            ->findIp($this->getWhmcsParamByKey('model')->dedicatedip)
            ->delete();
    }


}
