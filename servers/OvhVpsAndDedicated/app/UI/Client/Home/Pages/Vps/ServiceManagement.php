<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Client\Home\Pages\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\PageController;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Client\Home\Buttons\Vps\Disk;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Client\Home\Buttons\Vps\Diskss;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Client\Home\Buttons\Vps\IPs;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Client\Home\Buttons\Vps\Snapshot;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;

/**
 * Description of ControlPanel
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ServiceManagement extends BaseContainer implements ClientArea
{
    use WhmcsParamsApp;

    public function initContent()
    {
        $pageController = new PageController($this->getWhmcsEssentialParams());

        if ($pageController->vpsClientAreaDisks())
        {
            $this->addButton(new Diskss());
        }
        if ($pageController->vpsClientAreaSnapshots())
        {
            $this->addButton(new Snapshot());
        }
        if ($pageController->vpsClientAreaIps())
        {
            $this->addButton(new IPs());
        }

    }
}
