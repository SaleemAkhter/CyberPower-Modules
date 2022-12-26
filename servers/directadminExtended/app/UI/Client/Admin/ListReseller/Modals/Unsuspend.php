<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ListReseller\Modals;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ListReseller\Forms;

class Unsuspend extends BaseModal implements ClientArea
{
    protected $id    = 'unsuspendModal';
    protected $name  = 'unsuspendModal';
    protected $title = 'unsuspendModal';

    public function initContent()
    {
        $this->setSubmitButtonClassesDanger();
        $this->setModalTitleTypeDanger();
        $this->addForm(new Forms\Unsuspend());
    }
}
