<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Http\Actions;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Http\Admin\ServicePageIntegration;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\Instances\AddonController;

/**
 * Class AdminServicesTabFields
 *
 * @author <slawomir@modulesgarden.com>
 */
class AdminServicesTabFields extends AddonController
{
    public function execute($params = null)
    {
        return [ServicePageIntegration::class, 'index'];
    }
}
