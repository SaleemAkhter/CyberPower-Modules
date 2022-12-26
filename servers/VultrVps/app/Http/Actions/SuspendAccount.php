<?php

namespace ModulesGarden\Servers\VultrVps\App\Http\Actions;

use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\Core\App\Controllers\Instances\AddonController;

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
            $instance = (new InstanceFactory())->fromWhmcsParams();
            if ($instance->isRunning())
            {
                $instance->halt();
            }
            return 'success';
        }
        catch (\Exception $ex)
        {
            return $ex->getMessage();
        }
    }
}
