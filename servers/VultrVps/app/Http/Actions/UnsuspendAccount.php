<?php

namespace ModulesGarden\Servers\VultrVps\App\Http\Actions;

use ModulesGarden\Servers\VultrVps\App\Api\InstanceFactory;
use ModulesGarden\Servers\VultrVps\Core\App\Controllers\Instances\AddonController;

/**
 * Class UnsuspendAccount
 *
 * @author <slawomir@modulesgarden.com>
 */
class UnsuspendAccount extends AddonController
{
    public function execute($params = null)
    {
        try
        {
            $instance = (new InstanceFactory())->fromWhmcsParams();
            if (!$instance->isRunning())
            {
                $instance->start();
            }
            return 'success';
        }
        catch (\Exception $ex)
        {
            return $ex->getMessage();
        }
    }

}
