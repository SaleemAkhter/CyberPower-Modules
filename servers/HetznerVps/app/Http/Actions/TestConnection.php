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

use ModulesGarden\Servers\HetznerVps\App\Helpers\AccountActions;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\Http\ErrorPage;
use ModulesGarden\Servers\HetznerVps\Core\ServiceLocator;

class TestConnection extends AddonController
{
    use AccountActions;

    public function execute($params = null)
    {
        try {
            $this->params = $params;
            $this->api = new Api($this->params);
            $testConnection = $this->api->locations()->all();

            if (is_array($testConnection) && isset(reset($testConnection)->name)) {
                return [
                    'success' => true
                ];
            }
        } catch (\Exception $ex) {
            $errorPage = ServiceLocator::call(ErrorPage::class);

            $params['mgErrorDetails'] = $ex;

            return $errorPage->execute($params);
        }
    }
}