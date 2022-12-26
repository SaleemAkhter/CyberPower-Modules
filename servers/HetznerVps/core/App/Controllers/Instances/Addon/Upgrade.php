<?php

namespace ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\Addon;

use ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Interfaces\AddonController;
use ModulesGarden\Servers\HetznerVps\Core\Helper\DatabaseHelper;
use ModulesGarden\Servers\HetznerVps\Core\ServiceLocator;

/**
 * module update process
 */
class Upgrade extends \ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\AddonController implements AddonController
{
    /**
     * @var null|DatabaseHelper
     */
    protected $databaseHelper = null;

    public function execute($params = [])
    {
        if ($version == '')
        {
            $version = isset($this->params['version']) ? $this->params['version'] : $params['version'];
        }

        try
        {
            // after
            $return = ServiceLocator::call(\ModulesGarden\Servers\HetznerVps\Core\Configuration\Addon\Update\After::class)->execute(['version' => $version]);

            // update
            if (!isset($return['version']))
            {
                $return['version'] = $version;
            }
            $patchManager = ServiceLocator::call("patchManager")->run(/*$this->getConfig("version")*/ '', $version);

            // before
            $return = ServiceLocator::call(\ModulesGarden\Servers\HetznerVps\Core\Configuration\Addon\Update\Before::class)->execute($return);

            return $return;
        }
        catch (\Exception $ex)
        {
            ServiceLocator::call(\ModulesGarden\Servers\HetznerVps\Core\HandlerError\ErrorManager::class)->addError(self::class, $ex->getMessage(), $return);
        }
    }
}
