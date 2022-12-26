<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Actions;

use ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Instances\AddonController;

/**
 * Class MetaData
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class MetaData extends AddonController
{
    public function execute($params = null)
    {
        return [
            'DisplayName' => 'DirectAdmin Extended',
            'DefaultNonSSLPort' => '2222',
            'DefaultSSLPort' => '2222',
            'ListAccountsUniqueIdentifierDisplayName' => 'Domain',
            'ListAccountsUniqueIdentifierField' => 'domain',
            'ListAccountsProductField' => 'configoption1',
        ];
    }
}
