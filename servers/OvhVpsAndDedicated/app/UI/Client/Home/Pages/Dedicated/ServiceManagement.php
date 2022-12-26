<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Client\Home\Pages\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\PageController;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Client\Home\Buttons\Dedicated\Graphs;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Client\Home\Buttons\Dedicated\IPs;
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

        if ($pageController->dedicatedClientAreaIps())
        {
            $this->addButton(new IPs());
        }

        if ($pageController->dedicatedClientAreaGraphs())
        {
            $this->addButton(new Graphs());
        }

    }
}
