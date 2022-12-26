<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Forms;


class Create extends BaseEditModal implements ClientArea
{
    protected $id    = 'createRecordModal';
    protected $name  = 'createRecordModal';
    protected $title = 'createRecordModal';

    public function initContent()
    {
        $this->addForm(new Forms\Create());
    }
}
