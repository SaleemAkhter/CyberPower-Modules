<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\Base;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Modals\Dedicated\RebootConfirm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\BuildUrl;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\BaseModalButton;
use const DS;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonModal;

/**
 * Description of Reboot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class BaseControlButton extends ButtonModal implements ClientArea, AdminArea
{
    protected $id               = 'baseControlButton';
    protected $name             = 'baseControlButton';
    protected $icon             = 'lu-zmdi lu-zmdi-refresh';
    protected $title            = 'baseControlButton';
    protected $customActionName = "baseControlButton";

    protected $class      = [];

    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'base-control-button';


}
