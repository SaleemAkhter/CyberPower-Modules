<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Modals;



use ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Forms\CreateCustomFieldsAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class CreateCustomFieldsConfirm extends BaseEditModal implements AdminArea
{

    protected $id    = 'createCustomFieldConfirmModal';
    protected $name  = 'createCustomFieldConfirmModal';
    protected $title = 'createCustomFieldConfirmModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->addForm(new CreateCustomFieldsAction());

    }
}
