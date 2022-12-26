<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Home\Buttons;

use ModulesGarden\Servers\HetznerVps\App\UI\Home\Modals\PasswordResetConfirm;
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
class PasswordResetBaseModalButton extends ButtonModal implements ClientArea, AdminArea
{

    protected $id               = 'passwordResetButton'; // atrybut id w tag-u
    protected $name             = 'passwordResetButton'; // atrybut name w tagu
    protected $icon             = 'lu-zmdi lu-zmdi-shield-security';
    protected $title            = 'passwordResetButton';
    protected $customActionName = "vpsActions";

    public function initContent()
    {
        $this->initLoadModalAction(new PasswordResetConfirm());
    }


    public function getImage()
    {
        return BuildUrl::getAssetsURL() . DS . 'img' . DS . 'servers' . DS . $this->name . '.png';
    }

}
