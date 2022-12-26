<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Modals;

class OverwriteTTL extends ButtonCreate implements ClientArea
{
    protected $id    = 'overwriteTTLRecord';
    protected $name  = 'overwriteTTLRecord';
    protected $title = 'overwriteTTLRecord';
    protected $icon           = '';

    public function initContent()
    {
        $this->initLoadModalAction(new Modals\OverwriteTTL());
    }
}
