<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Buttons;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Modals\PasswordResetConfirm;
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
class PasswordResetBaseModalButton extends BaseModalButton implements ClientArea, AdminArea
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
