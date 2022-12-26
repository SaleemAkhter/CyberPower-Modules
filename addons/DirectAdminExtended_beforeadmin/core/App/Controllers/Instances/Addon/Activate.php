<?php

namespace ModulesGarden\DirectAdminExtended\Core\App\Controllers\Instances\Addon;

use ModulesGarden\DirectAdminExtended\Core\App\Controllers\Interfaces\AddonController;
use ModulesGarden\DirectAdminExtended\Core\DependencyInjection;
use ModulesGarden\DirectAdminExtended\Core\Helper\DatabaseHelper;
use ModulesGarden\DirectAdminExtended\Core\ModuleConstants;
use ModulesGarden\DirectAdminExtended\Core\ServiceLocator;

/**
 * Activate module actions
 */
class Activate extends \ModulesGarden\DirectAdminExtended\Core\App\Controllers\Instances\AddonController implements AddonController
{
    /**
     * @var null|DatabaseHelper
     */
    protected $databaseHelper = null;

    public function execute($params = [])
    {
        try
        {
            //Before module activation
            $return = ServiceLocator::call(\ModulesGarden\DirectAdminExtended\Core\Configuration\Addon\Activate\Before::class)->execute($params);
            if (!isset($return['status']))
            {
                $return['status'] = 'success';
            }

            //module activation process
            $return = $this->activate($return);

            if($return['status'] !== 'error')
            {
                //After module activation
                $return = ServiceLocator::call(\ModulesGarden\DirectAdminExtended\Core\Configuration\Addon\Activate\After::class)->execute($return);
            }

            return $return;
        }
        catch (\Exception $exc)
        {
            ServiceLocator::call(\ModulesGarden\DirectAdminExtended\Core\HandlerError\ErrorManager::class)->addError(self::class, $exc->getMessage(), $return);
            return [
                'status' => 'error',
                'description' => $exc->getMessage()
            ];
        }
    }

    protected function activate($params = [])
    {
        $this->databaseHelper = DependencyInjection::call(DatabaseHelper::class);

        if ($params['status'] === 'error')
        {
            return $params;
        }

        $isErrorCore     = $this->databaseHelper->performQueryFromFile(ModuleConstants::getFullPath('core', 'Database', 'schema.sql'));
        $isErrorApp     = $this->databaseHelper->performQueryFromFile(ModuleConstants::getFullPath('app', 'Database', 'schema.sql'));
        $isErrorDataCore = $this->databaseHelper->performQueryFromFile(ModuleConstants::getFullPath('core', 'Database', 'data.sql'));
        $isErrorDataApp = $this->databaseHelper->performQueryFromFile(ModuleConstants::getFullPath('app', 'Database', 'data.sql'));

        if ($isErrorCore || $isErrorDataCore || $isErrorApp || $isErrorDataApp)
        {
            return ['status' => 'error', 'description' => ServiceLocator::call('errorManager')->getFirstError()->getMessage()];
        }

        return ['status' => 'success'];
    }
}
