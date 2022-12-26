<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Forms\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Providers\DnsRecords;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;

class Delete extends BaseForm implements ClientArea
{
    protected $id    = 'deleteDnsMass';
    protected $name  = 'deleteDnsMass';
    protected $title = 'deleteDnsMass';

    public function getDefaultActions()
    {
        return ['massDelete'];
    }

    public function initContent()
    {
        $this->setFormType('massDelete')
            ->setProvider(new DnsRecords())
            ->setConfirmMessage('confirmMassDelete');
    }
}
