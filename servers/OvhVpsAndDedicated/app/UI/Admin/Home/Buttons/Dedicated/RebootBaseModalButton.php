<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\Base\BaseControlButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Modals\Dedicated\RebootConfirm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\BuildUrl;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;

/**
 * Description of Reboot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class RebootBaseModalButton extends BaseControlButton implements ClientArea, AdminArea
{

    protected $id               = 'rebootButton'; // atrybut id w tag-u
    protected $name             = 'rebootButton'; // atrybut name w tagu
    protected $title            = 'rebootButton';
    protected $customActionName = "dedicatedBoot";
    protected $icon = 'rebootButton';

    public function initContent()
    {
        $this->initLoadModalAction(new RebootConfirm());
    }

    public function getImage()
    {
        return BuildUrl::getAppAssetsURL() . DS . 'img' . DS . 'servers' . DS . $this->icon . '.png';
    }

}
