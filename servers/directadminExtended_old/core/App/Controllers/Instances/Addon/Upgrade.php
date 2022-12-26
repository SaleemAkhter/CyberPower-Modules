<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Instances\Addon;

use ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Interfaces\AddonController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\DatabaseHelper;
use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;

/**
 * module update process
 */
class Upgrade extends \ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Instances\AddonController implements AddonController
{
    /**
     * @var null|DatabaseHelper
     */
    protected $databaseHelper = null;

    public function execute($params = [])
    {

        $version = isset($this->params['version']) ? $this->params['version'] : $params['version'];

        try
        {
            // after
            $return = ServiceLocator::call(\ModulesGarden\Servers\DirectAdminExtended\Core\Configuration\Addon\Update\After::class)->execute(['version' => $version]);

            // update
            if (!isset($return['version']))
            {
                $return['version'] = $version;
            }
            $patchManager = ServiceLocator::call("patchManager")->run(/*$this->getConfig("version")*/ '', $version);

            // before
            $return = ServiceLocator::call(\ModulesGarden\Servers\DirectAdminExtended\Core\Configuration\Addon\Update\Before::class)->execute($return);

            return $return;
        }
        catch (\Exception $ex)
        {
            ServiceLocator::call(\ModulesGarden\Servers\DirectAdminExtended\Core\HandlerError\ErrorManager::class)->addError(self::class, $ex->getMessage(), $return);
        }
    }
}
