<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\DependencyInjection;

use ModulesGarden\OvhVpsAndDedicated\Core\ModuleConstants;
use ModulesGarden\OvhVpsAndDedicated\Core\FileReader\Reader;

/**
 * Load all services from yml file and mark them as shared in DI container
 * @author Mariusz Miodowski <mariusz@modulesgarden.com>
 * @package ModulesGarden\DomainOrdersExtended\Core\Services
 */
class Services
{
    /**
     * Services constructor.
     */
    public function __construct()
    {
        $this->load();
    }

    /**
     * Load all needed servies to DI container
     */
    protected function load()
    {
        foreach($this->getFilesList() as $file)
        {
            $servicesList   = Reader::read($file)->get();;
            if(!is_array($servicesList) || empty($servicesList))
            {
                continue;
            }

            $this->registerServices($servicesList);
        }
    }

    /**
     * Register all services in DI container
     * @param $servicesList
     */
    protected function registerServices($servicesList)
    {
        foreach($servicesList as $service)
        {
            Container::getInstance()->singleton($service);
        }
    }

    /**
     * Get file list with servies configuration
     * @return array
     */
    protected function getFilesList()
    {
        return [
            ModuleConstants::getFullPath('app', 'Config', 'di', 'services.yml'),
            ModuleConstants::getFullPath('core', 'Config', 'di', 'services.yml')
        ];
    }
}
