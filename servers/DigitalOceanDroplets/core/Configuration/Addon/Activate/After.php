<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration\Addon\Activate;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration\Addon\AbstractAfter;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\DatabaseHelper;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ModuleConstants;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ServiceLocator;

/**
 * Description of After
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class After extends AbstractAfter
{
    /**
     * @var DatabaseHelper 
     */
    protected $databaseHelper;

    public function __construct(DatabaseHelper $databaseHelper)
    {
        $this->databaseHelper = $databaseHelper;
    }

    /**
     * @return array
     */
    public function execute(array $params = [])
    {
        if ($params['status'] === 'error')
        {
            return $params;
        }

        $isErrorCore     = $this->databaseHelper->performQueryFromFile(ModuleConstants::getFullPath('core', 'Database', 'schema.sql'));
        $isErrorDataCore = $this->databaseHelper->performQueryFromFile(ModuleConstants::getFullPath('core', 'Database', 'data.sql'));
        $isErrorApp     = $this->databaseHelper->performQueryFromFile(ModuleConstants::getFullPath('app', 'Database', 'schema.sql'));
        $isErrorDataApp = $this->databaseHelper->performQueryFromFile(ModuleConstants::getFullPath('app', 'Database', 'data.sql'));
        
        if ($isErrorCore || $isErrorDataCore || $isErrorApp || $isErrorDataApp)
        {
            return ['status' => 'error', 'description' => ServiceLocator::call('errorManager')->getFirstError()->getMessage()];
        }

        return ['status' => 'success'];
    }
}
