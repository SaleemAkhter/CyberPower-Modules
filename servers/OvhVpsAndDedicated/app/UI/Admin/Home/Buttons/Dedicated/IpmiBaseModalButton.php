<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\Base\BaseControlButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Modals\Dedicated\IpmiAccess;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Modals\Dedicated\IpmiAccessModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\BuildUrl;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;

/**
 * Description of Reboot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class IpmiBaseModalButton extends BaseControlButton implements ClientArea, AdminArea
{

    protected $id               = 'ipmiButton'; // atrybut id w tag-u
    protected $name             = 'ipmiButton'; // atrybut name w tagu
    protected $title            = 'ipmiButton';
    protected $customActionName = "dedicatedIpmiAccess";

    public function initContent()
    {
        $this->initLoadModalAction(new IpmiAccess());
    }

    public function getImage()
    {
        return BuildUrl::getAppAssetsURL() . DS . 'img' . DS . 'servers' . DS . $this->name . '.png';
    }

}
