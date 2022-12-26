<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Buttons;

use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonRedirect;

/**
 * Class VpsListButton
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ListButton extends ButtonRedirect implements AdminArea
{
    protected $id    = 'vpsListButton';
    protected $name  = 'vpsListButton';
    protected $title = 'vpsListButton';
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-info-outline';
}
