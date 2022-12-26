<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Modals;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Forms\OverwriteTTL as Form;

class OverwriteTTL extends BaseEditModal implements ClientArea
{
    protected $id    = 'overwriteTTLModal';
    protected $name  = 'overwriteTTLModal';
    protected $title = 'overwriteTTLModal';

    public function initContent()
    {
        $this->addForm(new Form());
    }
}
