<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Pages;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Buttons\BackupsDisableBaseModalButton;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Buttons\BackupsEnableBaseModalButton;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Buttons\IPv6EnableBaseModalButton;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Buttons\PrivateNetworkEnableBaseModalButton;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ResponseTemplates\DataJsonResponse;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ServiceLocator;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class AdminPanel extends BaseContainer implements AdminArea, AjaxElementInterface
{
    protected $id = 'adminPanelAdditinalOpt';
    protected $vueComponent = true;   

    public function initContent()
    {
        //do nothing
    }
    
    public function returnAjaxData()
    {
        $this->loadAdminButtons();
        $buttonsData = $this->getherButtonsData();

        return (new DataJsonResponse($buttonsData));
    }

    protected function loadAdminButtons()
    {
        $manager = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\ServiceManager($this->whmcsParams);
        $serverMangager = $manager->getVM();
        if ($serverMangager->backupsEnabled)
        {
            $this->addButton(new BackupsDisableBaseModalButton());
        }
        else
        {
            $this->addButton(new BackupsEnableBaseModalButton());
        }

        if (!$serverMangager->privateNetworkingEnabled)
        {
            $this->addButton(new PrivateNetworkEnableBaseModalButton());
        }

        if (!$serverMangager->ipv6Enabled)
        {
            $this->addButton(new IPv6EnableBaseModalButton());
        }        
    }
    
    protected function getherButtonsData()
    {
        $buttonsData = [];
        $lang = ServiceLocator::call('lang');
        $lang->setContext();
        foreach ($this->buttons as $key => $button)
        {
            $htmlAttr = $button->getHtmlAttributes();
            $buttonsData [] = [
                'id' => $button->getId(),
                'namespace' => $button->getNamespace(),
                'htmlAtributes' => $button->getHtmlAttributes(),
                'buttonTitle' => $lang->T('buttons', 'actions', $button->getTitle()),
                'image' => $button->getImage(),
                'iconTitle' => $lang->T('serverCA', 'iconTitle', $button->getTitle()),
                'class' => implode(' ', $this->class),
                'clickAction' => $htmlAttr['@click'],
                'dataToggle' => $htmlAttr['data-toggle']
            ];
        }
        
        return $buttonsData;
    }
}
