<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Actions;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Instances\AddonController;

/**
 * Class TestConnection
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class TestConnection extends AddonController
{
    public function execute($params = null)
    {
        try{
            $testConnection = new \ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\TestConnection($params);
            return $testConnection->testConnection();
        }catch (\Exception $ex){
            return ['error' => $ex->getMessage()];
        }
    }
}
