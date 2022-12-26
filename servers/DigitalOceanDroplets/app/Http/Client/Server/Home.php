<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Http\Client\Server;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Pages\ControlPanel;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Pages\ManageService;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Network\Pages\NetworkInformation;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Product\Pages\ServerInformation;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\AbstractController;

/**
 * Description of Clientsservices
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Home extends AbstractController
{

    public function index()
    {
               
        return Helper\view()->addElement(ControlPanel::class)
                        ->addElement(ManageService::class)
                        ->addElement(ServerInformation::class)
                        ->addElement(NetworkInformation::class);
    }

}
