<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Buttons;

use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Modals\AssignClientModal;
use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Modals\AssignProductsModal;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonModal;

/**
 * Class AssignProducts
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class AssignProducts extends ButtonDataTableModalAction implements AdminArea
{
    protected $id    = 'assignProductsButton';
    protected $name  = 'assignProductsButton';
    protected $icon  = 'lu-zmdi lu-zmdi-assignment';
    protected $title = 'assignProducts';

    public function initContent()
    {
        $this->initLoadModalAction(new AssignProductsModal());
    }
}
