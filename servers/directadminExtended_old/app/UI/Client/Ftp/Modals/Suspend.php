<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Modals;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Forms;

class Suspend extends BaseModal implements ClientArea
{
    protected $id    = 'toggleSuspendModal';
    protected $name  = 'toggleSuspendModal';
    protected $title = 'toggleSuspendModal';

    public function initContent()
    {
        $this->setSubmitButtonClassesDanger()
            ->setModalTitleTypeDanger()
            ->addForm(new Forms\Suspend());
    }
}