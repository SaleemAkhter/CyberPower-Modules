<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Buttons;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Modals\ConfirmCreate;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonCreate;

/**
 * Description of Create
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Create extends ButtonCreate implements ClientArea, AdminArea
{
    protected $id             = 'createButton';
    protected $title          = 'createButton';

    public function initContent()
    {
        $this->initLoadModalAction(new ConfirmCreate());
    }

}
