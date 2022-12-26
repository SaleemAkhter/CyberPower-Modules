<?php

namespace ModulesGarden\Servers\AwsEc2\App\Http\Actions;

use ModulesGarden\Servers\AwsEc2\App\Http\Admin\ServicePageIntegration;
use ModulesGarden\Servers\AwsEc2\Core\App\Controllers\Instances\AddonController;

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
