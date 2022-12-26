<?php
namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Buttons;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Modals\PrivateNetworkEnableConfirm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\BaseModalButton;
use const DS;


/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class PrivateNetworkEnableBaseModalButton extends BaseModalButton implements AdminArea
{

    protected $id               = 'privateNetworkEnableButton'; // atrybut id w tag-u
    protected $name             = 'privateNetworkEnableButton'; // atrybut name w tagu
    protected $icon             = 'networkButton';
    protected $title            = 'privateNetworkEnableButton';

    public function initContent()
    {
        $this->initLoadModalAction(new PrivateNetworkEnableConfirm());
    }

    public function getImage()
    {
        return BuildUrl::getAssetsURL() . DS . 'img' . DS . 'servers' . DS . $this->icon . '.png';
    }

}
