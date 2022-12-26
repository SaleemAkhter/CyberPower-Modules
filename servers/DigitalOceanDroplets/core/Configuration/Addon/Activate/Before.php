<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration\Addon\Activate;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration\Addon\AbstractBefore;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ModuleConstants;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\ServiceLocator;
/**
 * Description of Before
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Before extends AbstractBefore
{

    /**
     * @return array
     */
    public function execute()
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
