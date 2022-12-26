<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Modals;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Forms\ChangeKernel;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Forms\Edit as EditForm;


/**
 * Class Edit
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Edit extends BaseEditModal implements ClientArea, AdminArea
{
    protected $id    = 'diskEdit';
    protected $name  = 'diskEdit';
    protected $title = 'diskEdit';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->addForm(new EditForm());
    }

}