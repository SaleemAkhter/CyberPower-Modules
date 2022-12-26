<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Pages;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Buttons\PasswordResetBaseModalButton;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Buttons\PowerOffBaseModalButton;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Buttons\PowerOnBaseModalButton;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Buttons\RebootBaseModalButton;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Buttons\ShutdownBaseModalButton;
use function ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\isAdmin;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ControlPanel extends BaseContainer implements ClientArea, AdminArea
{
    
    public function initContent()
    {
        
        if(isAdmin() === false ) {
            $pageController = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\PageController($this->whmcsParams);
            if ($pageController->clientAreaPowerOn()){
                $this->addButton(new PowerOnBaseModalButton());
            }
            if ($pageController->clientAreaPowerOff()){
                $this->addButton(new PowerOffBaseModalButton());
            }
            if ($pageController->clientAreaShutDown()){
                $this->addButton(new ShutdownBaseModalButton());
            }
            if ($pageController->clientAreaReboot()){
                $this->addButton(new RebootBaseModalButton());
            }
            if ($pageController->clientAreaResetPassword()) {
                $this->addButton(new PasswordResetBaseModalButton());
            }            
        }else{
            $this->addButton(new PowerOnBaseModalButton());
            $this->addButton(new PowerOffBaseModalButton());
            $this->addButton(new ShutdownBaseModalButton());
            $this->addButton(new RebootBaseModalButton);
            $this->addButton(new PasswordResetBaseModalButton());            
        }
        
      
    }

}
