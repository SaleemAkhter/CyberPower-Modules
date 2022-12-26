<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Modals;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Forms\ConsoleOpen;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseModal;

/**
 * Class ConsoleModal
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ConsoleModal extends BaseEditModal implements AdminArea
{

    protected $id    = 'consoleModal';
    protected $name  = 'consoleModal';
    protected $title = 'consoleModal';

    public function initContent()
    {
        
        $this->setModalSizeMedium();
        $this->addForm(new ConsoleOpen());
    }

}