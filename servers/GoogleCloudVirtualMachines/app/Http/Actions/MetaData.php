<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Http\Actions;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\Instances\AddonController;

/**
 * Class MetaData
 *
 * @author <slawomir@modulesgarden.com>
 */
class MetaData extends AddonController
{
    public function execute($params = null)
    {
        return [
            'DisplayName'    => 'Google Cloud Virtual Machines',
            'RequiresServer' => true
        ];
    }
}
