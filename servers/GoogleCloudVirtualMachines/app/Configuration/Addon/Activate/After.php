<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Configuration\Addon\Activate;

/**
 * Runs after module activation actions
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class After extends \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Configuration\Addon\Activate\After
{

    /**
     * @param array $params
     * @return array
     */
    public function execute(array $params = [])
    {
        $return = parent::execute($params);
        
        return $return;
    }
}
