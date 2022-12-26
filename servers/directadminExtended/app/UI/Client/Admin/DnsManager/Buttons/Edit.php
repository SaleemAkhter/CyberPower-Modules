<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Modals\Edit as Modal;


class Edit extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'editRecord';
    protected $name  = 'editRecord';
    protected $title = 'editRecord';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', [dataRow.name, dataRow.user], null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modal());

        return parent::returnAjaxData();
    }
}
