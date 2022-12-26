<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Modals;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Forms;

class Suspend extends BaseModal implements ClientArea
{
    protected $id    = 'suspendModal';
    protected $name  = 'suspendModal';
    protected $title = 'suspendModal';

    public function initContent()
    {
        $this->setSubmitButtonClassesDanger();
        $this->setModalTitleTypeDanger();
        $this->addForm(new Forms\Suspend());
    }
}
