<?php


namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Buttons;


use ModulesGarden\Servers\VultrVps\App\Helpers\HostingBuildUrl;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Modals\RebootInstance;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Home\Modals\ReinstallModal;
use ModulesGarden\Servers\VultrVps\Core\Helper\BuildUrl;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;

class ManagamentButton extends VpsActionButton implements AdminArea, ClientArea
{

    protected $href;

    /**
     * ManagamentButton constructor.
     */
    public function __construct($baseId = null)
    {
        parent::__construct($baseId);
        $this->href = (new HostingBuildUrl())->getUrl($baseId);
        $this->setIconFileName($baseId.'.png');
    }


    public function initContent()
    {
        $this->addHtmlAttribute("onclick", "location.href = '{$this->href}'; return false;");
        return $this;
    }
}