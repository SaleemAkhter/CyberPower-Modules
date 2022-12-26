<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Http\Client\Server;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Tasks\Pages\TaskList;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\AbstractController;

/**
 * Description of Clientsservices
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Task extends AbstractController
{

    public function index()
    {
        try
        {
            $pageController = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\PageController($this->whmcsParams);
            if (!$pageController->clientAreaTask()) {
                return \ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\redirectByUrl('clientarea.php', [
                    'action' => 'productdetails',
                    'id'     => $this->getRequest()->get('id')
                ]);
            }
            return Helper\view()->addElement(TaskList::class);
        }
        catch (Exception $ex)
        {
            
        }
    }

}
