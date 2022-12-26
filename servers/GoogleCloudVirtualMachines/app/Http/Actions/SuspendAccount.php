<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Http\Actions;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\GoogleServiceComputeFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\InstanceFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ProjectFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\Instances\AddonController;

/**
 * Class SuspendAccount
 *
 * @author <slawomir@modulesgarden.com>
 */
class SuspendAccount extends AddonController
{

    public function execute($params = null)
    {
        try
        {
            $instace = (new InstanceFactory())->fromParams();
            $compute = (new GoogleServiceComputeFactory())->fromParams();
            $poject = (new ProjectFactory())->fromParams();
            $compute->instances->stop($poject , $instace->getZone(), $instace->getId() );
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
            return $ex->getMessage();
        }
    }
}
