<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Http\Client\Server;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Kernel\Pages\KernelManage;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\AbstractController;

/**
 * Description of Clientsservices
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Kernel extends AbstractController {

    public function index() {
        try {
            $pageController = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\PageController($this->whmcsParams);
            if (!$pageController->clientAreaKernel()) {
                return \ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\redirectByUrl('clientarea.php', [
                    'action' => 'productdetails',
                    'id'     => $this->getRequest()->get('id')
                ]);
            }
            return Helper\view()->addElement(KernelManage::class);
        }
        catch (Exception $ex) {

        }
    }

}
