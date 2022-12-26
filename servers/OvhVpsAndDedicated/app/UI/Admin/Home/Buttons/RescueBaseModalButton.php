<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\Base\BaseControlButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\BuildUrl;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Modals\RescueConfirm;
use const DS;


/**
 * Description of Reboot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class RescueBaseModalButton extends BaseControlButton implements ClientArea, AdminArea
{

    protected $id               = 'rescueButton'; // atrybut id w tag-u
    protected $name             = 'rescue'; // atrybut name w tagu
    protected $icon             = 'lu-zmdi lu-zmdi-refresh';
    protected $title            = 'rescueButton';


    public function initContent()
    {
        $this->initLoadModalAction(new RescueConfirm());
    }

    public function getImage()
    {
        return BuildUrl::getAppAssetsURL() . DS . 'img' . DS . 'servers' . DS . $this->name . '.png';
    }

}
