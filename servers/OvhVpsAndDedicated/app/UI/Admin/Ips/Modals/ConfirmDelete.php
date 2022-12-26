<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Modals;


use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Ips\Forms\Delete;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class ConfirmDelete extends BaseModal implements ClientArea, AdminArea
{

    protected $id    = 'confirmDeleteModal';
    protected $name  = 'confirmDeleteModal';
    protected $title = 'confirmDeleteModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->setConfirmButtonDanger();
        $this->addForm(new Delete());
    }

}
