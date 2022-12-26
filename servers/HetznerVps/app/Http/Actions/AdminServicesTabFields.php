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

use ModulesGarden\Servers\HetznerVps\App\Helpers\AppParams;
use ModulesGarden\Servers\HetznerVps\App\Http\Admin\Home;
use ModulesGarden\Servers\HetznerVps\App\UI\Validators\Validator;
use ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\Servers\HetznerVps\Core\UI\Traits\WhmcsParams;

class AdminServicesTabFields extends AddonController
{
    use WhmcsParams;

    /**
     * @var AddonUpgradeService
     */
    private $addonUpgradeService;

    public function execute($params = null)
    {
        $action = $this->getRequestValue('mg-action') ? $this->request->get('mg-action') : 'index';

        if ($params['status'] != "Active" || !$params["customfields"]["serverID"]) {
            return [];
        }
        try {
            (new AppParams())->initFromWhmcsParams();
            return [Home::class, $action];
        } catch (\Exception $ex) {

            return ["Error" => "<span style='color: red'>" . $ex->getMessage() . "</span>"];
        }

    }
}