<?php
namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Buttons;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Modals\IPv6EnableConfirm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\BaseModalButton;
use const DS;


/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class IPv6EnableBaseModalButton extends BaseModalButton implements AdminArea
{

    protected $id               = 'iPv6Enable'; // atrybut id w tag-u
    protected $name             = 'iPv6Enable'; // atrybut name w tagu
    protected $icon             = 'networkButton';
    protected $title            = 'iPv6Enable';

    public function initContent()
    {
        $this->initLoadModalAction(new IPv6EnableConfirm());
    }

    public function getImage()
    {
        return BuildUrl::getAssetsURL() . DS . 'img' . DS . 'servers' . DS . $this->icon . '.png';
    }

}
