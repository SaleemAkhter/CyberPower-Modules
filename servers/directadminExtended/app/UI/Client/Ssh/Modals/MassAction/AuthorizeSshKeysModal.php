<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Modals\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Forms\MassAction\AuthorizeSshKeysForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;

class AuthorizeSshKeysModal extends BaseEditModal implements ClientArea
{
    protected $id    = 'authorizeManyModal';
    protected $name  = 'authorizeManyModal';
    protected $title = 'authorizeManyModal';

    public function initContent()
    {
        $this->addForm(new AuthorizeSshKeysForm());
    }
}