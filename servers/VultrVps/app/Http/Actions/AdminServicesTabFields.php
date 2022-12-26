<?php

namespace ModulesGarden\Servers\VultrVps\App\Http\Actions;

use ModulesGarden\Servers\VultrVps\App\Http\Admin\Home;
use ModulesGarden\Servers\VultrVps\Core\App\Controllers\Instances\AddonController;

/**
 * Class AdminServicesTabFields
 *
 * @author <slawomir@modulesgarden.com>
 */
class AdminServicesTabFields extends AddonController
{
    public function execute($params = null)
    {
        try {
            $action = $this->getRequestValue('mg-action', 'index');
            return [Home::class, $action];
        } catch (\Exception $ex) {
            return ["Error" => "<span style='color: red'>" . $ex->getMessage() . "</span>"];
        }
    }
}
