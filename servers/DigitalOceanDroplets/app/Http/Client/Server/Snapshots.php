<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Http\Client\Server;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Pages\SnapshotList;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\AbstractController;

/**
 * Description of Clientsservices
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Snapshots extends AbstractController
{

    public function index()
    {
        try
        {
            $pageController = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\PageController($this->whmcsParams);
            if (!$pageController->clientAreaSnapshots()) {
                return \ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\redirectByUrl('clientarea.php', [
                    'action' => 'productdetails',
                    'id'     => $this->getRequest()->get('id')
                ]);
            }
            return Helper\view()->addElement(SnapshotList::class);
        }
        catch (Exception $ex)
        {

        }
    }

}
