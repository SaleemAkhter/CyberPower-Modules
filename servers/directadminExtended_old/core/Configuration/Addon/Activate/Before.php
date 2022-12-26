<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\Configuration\Addon\Activate;

use \ModulesGarden\Servers\DirectAdminExtended\Core\Configuration\Addon\AbstractBefore;
use \ModulesGarden\Servers\DirectAdminExtended\Core\ModuleConstants;
use \ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;
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
