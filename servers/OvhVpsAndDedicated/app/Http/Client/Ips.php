<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Client;

use Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Http\AbstractController;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Client\Ips\Pages\IpsListDedicated;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Client\Ips\Pages\IpsList;

/**
 * Description of Clientsservices
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Ips extends ClientBaseController
{
    public function index()
    {
        if (!$this->getPageController()->vpsClientAreaIps())
        {
            return $this->redirectToMainServicePage();
        }

        return Helper\view()->addElement(IpsList::class);

    }
}
