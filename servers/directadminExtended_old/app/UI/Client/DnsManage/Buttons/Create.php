<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\DnsManage\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\DnsManage\Modals;

class Create extends ButtonCreate implements ClientArea
{
    protected $id    = 'createRecordButton';
    protected $name  = 'createRecordButton';
    protected $title = 'createRecordButton';

    public function initContent()
    {
        $this->initLoadModalAction(new Modals\Create());
    }
}