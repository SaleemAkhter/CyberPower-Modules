<?php
/**********************************************************************
 * HetznerVps developed. (26.03.19)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 **********************************************************************/

namespace ModulesGarden\Servers\HetznerVps\App\Http\Actions;

use Exception;
use ModulesGarden\Servers\HetznerVps\App\Helpers\AccountActions;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\AddonController;

/**
 * Susspend WHMCS Account and power off VM
 */
class SuspendAccount extends AddonController
{
    use AccountActions;

    public function execute($params = null)
    {
        $this->params = $params;
        $this->api = new Api($this->params);

        try {
            $this->checkServerIDIsNotEmpty();
            $this->api->servers()->get($this->api->getClient()->getServerID())->shutdown();
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
        return 'success';
    }
}