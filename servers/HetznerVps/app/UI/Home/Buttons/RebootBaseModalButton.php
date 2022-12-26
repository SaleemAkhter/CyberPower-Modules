<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Home\Buttons;

use ModulesGarden\Servers\HetznerVps\App\UI\Home\Modals\RebootConfirm;
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
class RebootBaseModalButton extends ButtonModal implements ClientArea, AdminArea
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
