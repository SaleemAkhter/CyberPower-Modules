<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Actions;

use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Instances\AddonController;

/**
 * Class MetaData
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class MetaData extends AddonController
{
    public function execute($params = null)
    {
        return [
            'DisplayName'    => 'OVH',
            'RequiresServer' => true
        ];
    }
}
