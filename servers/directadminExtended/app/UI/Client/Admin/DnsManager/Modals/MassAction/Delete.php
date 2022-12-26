<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Modals\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Forms;

class Delete extends BaseModal implements ClientArea
{
    protected $id    = 'massDeleteDnsModal';
    protected $name  = 'massDeleteDnsModal';
    protected $title = 'massDeleteDnsModal';

    public function initContent()
    {
        $this->setModalTitleTypeDanger()
            ->setSubmitButtonClassesDanger()
            ->addForm(new Forms\MassAction\Delete());
    }
}
