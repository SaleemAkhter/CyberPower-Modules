<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Http\Actions;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\GoogleServiceComputeFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\InstanceFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ProjectFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Enum\CustomField;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Events\VmDeleted;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\ApiClient;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\CustomFieldUpdate;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits\ProductSetting;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\queue;

/**
 * Class TerminateAccount
 *
 * @author <slawomir@modulesgarden.com>
 */
class TerminateAccount extends CreateAccount
{
    use WhmcsParams, ProductSetting, ApiClient, CustomFieldUpdate;

    public function execute($params = null)
    {
        try
        {
            $instace = (new InstanceFactory())->fromParams();
            $compute = (new GoogleServiceComputeFactory())->fromParams();
            $poject = (new ProjectFactory())->fromParams();
            //stop            
            $compute->instances->stop($poject , $instace->getZone(), $instace->getId() );            
            //read
            $this->instance = $this->compute()->instances->get($poject,$instace->getZone(), $instace->getId());
            $this->instance->setZone($this->getWhmcsCustomField(CustomField::ZONE));
            //delete public ip
            if($this->instance->getNetworkInterfaces()[0]->accessConfigs[0]->natIP){
                $this->deleteRegionalIpv4Address();
            }
            //delete
            $compute->instances->delete($poject , $instace->getZone(), $instace->getId() );
            $this->customFieldUpdate(CustomField::INSTANCE_ID, "");
            $this->customFieldUpdate(CustomField::ZONE, "");
            $this->customFieldUpdate(CustomField::REGION, "");
            return 'success';
        }
        catch (\Exception $ex)
        {
            logModuleCall(
                'GoogleCloudVirtualMachines',
                __CLASS__,
                $params,
                $ex->getMessage(),
                $ex->getTraceAsString()
            );
            return $ex->getMessage();
        }
    }

}
