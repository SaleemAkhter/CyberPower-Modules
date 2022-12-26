<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Client;

use Exception;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Client\Disks\Pages\DisksList;

/**
 * Description of Disk
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Disks extends ClientBaseController
{

    public function index()
    {
        try
        {
            if (!$this->getPageController()->vpsClientAreaDisks()) {
                return $this->redirectToMainServicePage();
            }
            return Helper\view()->addElement(DisksList::class);
        }
        catch (Exception $ex)
        {
            \logModuleCall('OvhVpsAndDedicated', __CLASS__, var_export($ex, true), []);
        }
    }

}
