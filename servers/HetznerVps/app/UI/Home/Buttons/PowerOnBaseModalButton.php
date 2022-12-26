<?php
namespace ModulesGarden\Servers\HetznerVps\App\UI\Home\Buttons;

use ModulesGarden\Servers\HetznerVps\App\UI\Home\Modals\PowerOnConfirm;
use ModulesGarden\Servers\HetznerVps\Core\Helper\BuildUrl;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Buttons\ButtonModal;


/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class PowerOnBaseModalButton extends ButtonDataTableModalAction implements ClientArea, AdminArea
{

    protected $id               = 'powerOnButton'; // atrybut id w tag-u
    protected $name             = 'powerOnButton'; // atrybut name w tagu
    protected $icon             = 'lu-zmdi lu-zmdi-power';
    protected $title            = 'powerOnButton';
    protected $customActionName = "vpsActions";

    public function initContent()
    {
        $this->initLoadModalAction(new PowerOnConfirm());
    }

    public function getImage()
    {
        return BuildUrl::getAssetsURL() . DS . 'img' . DS . 'servers' . DS . $this->name . '.png';
    }

}
