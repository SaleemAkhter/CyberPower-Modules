<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Buttons;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Modals\CreateConfigurableOptionsConfirm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\AddIconModalButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonCreate;

/**
 * Description of Reboot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class CreateConfigurableOptionsBaseModalButton extends ButtonCreate implements AdminArea
{

    protected $id             = 'createCOBaseModalButton'; // atrybut id w tag-u
    protected $name           = 'createCOBaseModalButton'; // atrybut name w tagu
    protected $title          = 'createCOBaseModalButton';

    protected $class          = ['lu-btn lu-btn--success'];

    protected $htmlAttributes = [
        'href' => 'javascript:;',
    ];

    public function initContent()
    {
        $this->initLoadModalAction(new CreateConfigurableOptionsConfirm());
    }

}
