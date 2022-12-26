<?php
namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Buttons;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Modals\BackupsEnableConfirm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\BaseModalButton;
use const DS;


/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class BackupsEnableBaseModalButton extends BaseModalButton implements AdminArea
{

    protected $id               = 'backupsEnableButton'; // atrybut id w tag-u
    protected $name             = 'backupsEnableButton'; // atrybut name w tagu
    protected $icon             = 'backupsButton';
    protected $title            = 'backupsEnableButton';

    public function initContent()
    {
        $this->initLoadModalAction(new BackupsEnableConfirm());
    }

    public function getImage()
    {
        return BuildUrl::getAssetsURL() . DS . 'img' . DS . 'servers' . DS . $this->icon . '.png';
    }

}
