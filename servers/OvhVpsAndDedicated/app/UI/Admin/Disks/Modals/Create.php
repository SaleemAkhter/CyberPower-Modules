<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Modals;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Forms\Create as CreateForm;

/**
 * Class Create
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Create extends BaseEditModal implements ClientArea, AdminArea
{
    protected $id    = 'diskCreate';
    protected $name  = 'diskCreate';
    protected $title = 'diskCreate';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->addForm(new CreateForm());
    }

}