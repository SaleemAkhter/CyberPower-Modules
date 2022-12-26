<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Modals;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Forms\Create as CreateForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Forms\ReverseForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseEditModal;

/**
 * Class Create
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ReverseModal extends BaseEditModal implements ClientArea, AdminArea
{

    protected $id    = 'ipReverseModal';
    protected $name  = 'ipReverseFormModal';
    protected $title = 'ipReverseFormModal';

    public function initContent()
    {
        $this->addForm(new ReverseForm());
    }

}