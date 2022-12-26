<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Http\Actions;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\GoogleClientFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ProjectFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\Instances\AddonController;
use function ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\sl;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\WhmcsParams;

/**
 * Class MetaData
 *
 * @author <pawelk@modulesgarden.com>
 */
class TestConnection extends AddonController
{

    public function execute($params = null)
    {
        try
        {
            //init api
            sl('ApiClient')->setGoogleClient((new GoogleClientFactory())->fromParams())
                                 ->setProject((new ProjectFactory())->fromParams());
            //test connection
            $service = new \Google_Service_Compute(sl('ApiClient')->getGoogleClient());
            $service->projects->get(sl('ApiClient')->getProject());
            return ['success' => true];
        }
        catch (\Exception $ex)
        {
            return ['error' => $ex->getMessage()];
        }
    }
}
