<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Home\Buttons;

use ModulesGarden\Servers\HetznerVps\App\UI\Home\Modals\ShutdownConfirm;
use ModulesGarden\Servers\HetznerVps\Core\Helper\BuildUrl;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonModal;
use const DS;

/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ShutdownBaseModalButton extends ButtonModal implements ClientArea, AdminArea
{

    protected $id               = 'shutdownButton'; // atrybut id w tag-u
    protected $name             = 'shutdownButton'; // atrybut name w tagu
    protected $icon             = 'lu-zmdi lu-zmdi-input-power';
    protected $title            = 'shutdownButton';
    protected $customActionName = "vpsActions";

    public function initContent()
    {
        $this->initLoadModalAction(new ShutdownConfirm());
    }


    public function getImage()
    {
        return BuildUrl::getAssetsURL() . DS . 'img' . DS . 'servers' . DS . $this->name . '.png';
    }

}
