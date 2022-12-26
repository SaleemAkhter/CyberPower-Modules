<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Buttons;

use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Modals\AssignClientModal;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonDataTableModalAction;


/**
 * Class AssignClientButton
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class AssignClientButton extends ButtonDataTableModalAction implements AdminArea
{
    protected $id             = 'assignClient';
    protected $name           = 'assignClient';
    protected $icon           = 'lu-zmdi lu-zmdi-assignment-account';
    protected $title          = 'assignClient';

    public function initContent()
    {
        $this->initLoadModalAction(new AssignClientModal());
    }
}
