<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Modals;


use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Forms\EditSSHForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\ModalTabsEdit;

class EditSSHModal extends ModalTabsEdit implements ClientArea
{
    protected $id    = 'editModal';
    protected $name  = 'editModal';
    protected $title = 'editModal';

    public function initContent()
    {
        $this->addForm(new EditSSHForm());
    }
}