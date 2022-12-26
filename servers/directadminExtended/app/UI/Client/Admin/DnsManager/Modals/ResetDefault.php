<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Modals;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Forms;

class ResetDefault extends BaseModal implements ClientArea
{
    protected $id    = 'resetDefaultModal';
    protected $name  = 'resetDefaultModal';
    protected $title = 'resetDefaultModal';

    public function initContent()
    {
        $this->setModalTitleTypeDanger()
            ->setSubmitButtonClassesDanger()
            ->addForm(new Forms\ResetDefault());

    }
}
