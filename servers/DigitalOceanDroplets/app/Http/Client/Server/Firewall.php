<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Http\Client\Server;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Adapters\Client;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Pages\FirewallList;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Pages\FirewallRules;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\AbstractController;

/**
 * Description of Clientsservices
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Firewall extends AbstractController {

    public function index() {
        try {
            $pageController = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\PageController($this->whmcsParams);
            if (!$pageController->clientAreaFirewall()) {
                return \ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\redirectByUrl('clientarea.php', [
                    'action' => 'productdetails',
                    'id'     => $this->getRequest()->get('id')
                ]);
            }
            if($this->getRequest()->get('firewallid') && $pageController->checkIsOwenrFirewall($this->getRequest()->get('firewallid'))){
                return Helper\view()->addElement(FirewallRules::class);
            }
            return Helper\view()->addElement(FirewallList::class);
        }
        catch (Exception $ex) {

        }
    }

}
