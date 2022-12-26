<?php

namespace ModulesGarden\Servers\AwsEc2\App\Http\Actions;

use \ModulesGarden\Servers\AwsEc2\Core\App\Controllers\Instances\AddonController;

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
            'DisplayName'    => 'Amazon EC2',
            'RequiresServer' => true
        ];
    }
}
