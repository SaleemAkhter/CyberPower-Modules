<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Configuration\Addon\Activate;

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Configuration\Addon\AbstractBefore;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\ModuleConstants;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\ServiceLocator;
/**
 * Runs before module activation actions
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Before extends AbstractBefore
{

    /**
     * @param array $params
     * @return array
     */
    public function execute(array $params = [])
    {
        $path = ModuleConstants::getModuleRootDir() . DS . 'storage';

        if (is_writable($path) === false || is_readable($path) === false)
        {
            $params['status'] = 'error';
            $params['description'] .= PHP_EOL . ServiceLocator::call('lang')
                    ->addReplacementConstant('storage_path', ModuleConstants::getFullPath('storage'))
                    ->absoluteT('permissionsStorage');
        }

        return $params;
    }
}
