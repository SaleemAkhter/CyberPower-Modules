<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Modals;

use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Forms\AssignClientForm;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseEditModal;


/**
 * Class AssignClientModal
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class AssignClientModal extends BaseEditModal implements AdminArea
{
    protected $id        = 'AssignClientModal';
    protected $name      = 'AssignClientModal';
    protected $title     = 'AssignClientModal';


    public function initContent()
    {
        $this->addForm(new AssignClientForm());
    }
}