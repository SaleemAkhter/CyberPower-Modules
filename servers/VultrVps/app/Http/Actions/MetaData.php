<?php

namespace ModulesGarden\Servers\VultrVps\App\Http\Actions;

use ModulesGarden\Servers\VultrVps\Core\App\Controllers\Instances\AddonController;

/**
 * Class MetaData
 *
 * @author <slawomir@modulesgarden.com>
 */
class MetaData extends AddonController
{
    public function execute($params = null)
    {
        return [
            'DisplayName'    => 'Vultr VPS',
            'RequiresServer' => true
        ];
    }
}
