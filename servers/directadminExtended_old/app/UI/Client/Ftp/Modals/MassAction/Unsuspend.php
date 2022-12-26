<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Modals\MassAction;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Forms;

class Unsuspend extends BaseModal implements ClientArea
{
    protected $id    = 'massUnsuspendModal';
    protected $name  = 'massUnsuspendModal';
    protected $title = 'massUnsuspendModal';

    public function initContent()
    {
        $this->setSubmitButtonClassesDanger();
        $this->setModalTitleTypeDanger();
        $this->addForm(new Forms\MassAction\Unsuspend());
    }
}