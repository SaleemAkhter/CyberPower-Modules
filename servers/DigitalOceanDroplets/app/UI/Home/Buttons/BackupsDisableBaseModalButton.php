<?php
namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Buttons;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Modals\BackupsDisableConfirm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\BaseModalButton;
use const DS;


/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class BackupsDisableBaseModalButton extends BaseModalButton implements AdminArea
{

    protected $id               = 'backupsDisableButton'; // atrybut id w tag-u
    protected $name             = 'backupsDisableButton'; // atrybut name w tagu
    protected $icon             = 'backupsButton';
    protected $title            = 'backupsDisableButton';

    public function initContent()
    {
        $this->initLoadModalAction(new BackupsDisableConfirm());
    }

    public function getImage()
    {
        return BuildUrl::getAssetsURL() . DS . 'img' . DS . 'servers' . DS . $this->icon . '.png';
    }

}
