<?php
namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\Base\BaseControlButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Modals\PowerOnConfirm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\BuildUrl;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;


/**
 * Description of Reboot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class PowerOnBaseModalButton extends BaseControlButton implements ClientArea, AdminArea
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
        return BuildUrl::getAppAssetsURL() . DS . 'img' . DS . 'servers' . DS . $this->name . '.png';
    }

}
