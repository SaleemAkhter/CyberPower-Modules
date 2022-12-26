<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Modals;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Forms\Create as CreateForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseEditModal;

/**
 * Class Create
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Create extends BaseEditModal implements ClientArea, AdminArea
{

    protected $id    = 'ipCreateModal';
    protected $name  = 'ipCreateModal';
    protected $title = 'ipCreateModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->addForm(new CreateForm());
    }

}