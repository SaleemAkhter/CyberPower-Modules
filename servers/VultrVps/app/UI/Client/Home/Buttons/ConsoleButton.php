<?php


namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Buttons;


use ModulesGarden\Servers\VultrVps\App\Helpers\HostingBuildUrl;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Modals\RebootInstance;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Modals\ReinstallModal;
use ModulesGarden\Servers\VultrVps\Core\Helper\BuildUrl;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;

class ConsoleButton extends VpsActionButton implements AdminArea, ClientArea
{

    protected $id = 'consoleButton';
    protected $name = 'consoleButton';
    protected $title = 'consoleButton';

    public function initContent()
    {
        $this->setIconFileName('novnc.png');
        $url  = (new HostingBuildUrl())->getUrl('home','console');
        $this->addHtmlAttribute("onclick", "window.open('{$url}', '', 'width=900,height=700'); return false;");
    }
}