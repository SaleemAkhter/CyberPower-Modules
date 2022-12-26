<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Home\Pages;

use ModulesGarden\Servers\HetznerVps\App\UI\Home\Buttons\PasswordResetBaseModalButton;
use ModulesGarden\Servers\HetznerVps\App\UI\Home\Buttons\PowerOffBaseModalButton;
use ModulesGarden\Servers\HetznerVps\App\UI\Home\Buttons\PowerOnBaseModalButton;
use ModulesGarden\Servers\HetznerVps\App\UI\Home\Buttons\RebootBaseModalButton;
use ModulesGarden\Servers\HetznerVps\App\UI\Home\Buttons\ShutdownBaseModalButton;
use ModulesGarden\Servers\HetznerVps\App\UI\Home\Helpers\EnabledOptions;
use ModulesGarden\Servers\HetznerVps\Core\Helper\BuildUrl;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AjaxElementInterface;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\isAdmin;
use ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

/**
 * Description of Product
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ControlPanel extends BaseContainer implements ClientArea, AdminArea, AjaxElementInterface
{
    
    public function initContent()
    {
        $this->addButton(new PowerOnBaseModalButton());
        $this->addButton(new PowerOffBaseModalButton());
        $this->addButton(new ShutdownBaseModalButton());
        $this->addButton(new RebootBaseModalButton);
        $this->addButton(new PasswordResetBaseModalButton());
    }

    public function returnAjaxData()
    {
        // TODO: Implement returnAjaxData() method.
    }
}
