<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\Base\BaseControlButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Modals\ReinstallConfirm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\BuildUrl;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;


/**
 * Description of Reboot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ReinstallBaseModalButton extends BaseControlButton implements ClientArea, AdminArea
{

    protected $id               = 'reinstallButton'; // atrybut id w tag-u
    protected $name             = 'rebuild'; // atrybut name w tagu
    protected $title            = 'reinstallButton';
    protected $customActionName = "vpsReinstall";

    public function initContent()
    {
        $this->initLoadModalAction(new ReinstallConfirm());
    }

    public function getImage()
    {
        return BuildUrl::getAppAssetsURL() . DS . 'img' . DS . 'servers' . DS . $this->name . '.png';
    }

}
