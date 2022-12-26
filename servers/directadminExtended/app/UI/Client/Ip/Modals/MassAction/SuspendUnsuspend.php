<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Modals\MassAction;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Forms;

class SuspendUnsuspend extends BaseModal implements ClientArea
{
    protected $id    = 'massSuspendModal';
    protected $name  = 'massSuspendModal';
    protected $title = 'massSuspendModal';

    public function initContent()
    {
        $this->setSubmitButtonClassesDanger();
        $this->setModalTitleTypeDanger();
        $this->addForm(new Forms\MassAction\SuspendUnsuspend());
    }
}