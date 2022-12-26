<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Modals;



use ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Forms\CreateConfigurableAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class CreateConfigurableOptionsConfirm extends BaseEditModal implements AdminArea
{

    protected $id    = 'createCOConfirmModal';
    protected $name  = 'createCOConfirmModal';
    protected $title = 'createCOConfirmModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->addForm(new CreateConfigurableAction());

    }

}
