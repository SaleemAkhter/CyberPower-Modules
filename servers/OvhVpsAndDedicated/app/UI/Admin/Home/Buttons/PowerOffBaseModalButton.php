<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\Base\BaseControlButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Modals\PowerOffConfirm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\BuildUrl;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use const DS;


/**
 * Description of Reboot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class PowerOffBaseModalButton extends BaseControlButton implements ClientArea, AdminArea
{

    protected $id               = 'powerOffButton';
    protected $name             = 'powerOffButton';
    protected $icon             = 'lu-zmdi lu-zmdi-power';
    protected $title            = 'powerOffButton';
    protected $customActionName = "vpsActions";

    public function initContent()
    {
        $this->initLoadModalAction(new PowerOffConfirm());
    }

    public function getImage()
    {
        return BuildUrl::getAppAssetsURL() . DS . 'img' . DS . 'servers' . DS . $this->name . '.png';
    }

}
