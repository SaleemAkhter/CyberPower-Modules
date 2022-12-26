<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Buttons;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Modals\RebootConfirm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\BaseModalButton;
use const DS;

/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class RebootBaseModalButton extends BaseModalButton implements ClientArea, AdminArea
{

    protected $id               = 'rebootButton'; // atrybut id w tag-u
    protected $name             = 'rebootButton'; // atrybut name w tagu
    protected $icon             = 'lu-zmdi lu-zmdi-refresh';
    protected $title            = 'rebootButton';
    protected $customActionName = "vpsActions";

    public function initContent()
    {
        $this->initLoadModalAction(new RebootConfirm());
    }

    public function getImage()
    {
        return BuildUrl::getAssetsURL() . DS . 'img' . DS . 'servers' . DS . $this->name . '.png';
    }

}
