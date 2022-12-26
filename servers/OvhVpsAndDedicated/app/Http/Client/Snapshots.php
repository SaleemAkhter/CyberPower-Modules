<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Client;

use Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Client\Snapshots\Pages\SnapshotPage;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper;


/**
 * Description of Clientsservices
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Snapshots extends ClientBaseController
{

    public function index()
    {
        try
        {
            if (!$this->getPageController()->vpsClientAreaSnapshots()) {
                return $this->redirectToMainServicePage();
            }
            return Helper\view()->addElement(SnapshotPage::class);
        }
        catch (Exception $exception)
        {
            \logModuleCall('OvhVpsAndDedicated', __CLASS__, var_export($exception, true), []);
        }
    }

}
