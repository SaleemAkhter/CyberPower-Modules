<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\DnsManager\Modals;

class ResetDefault extends ButtonCreate implements ClientArea
{
    protected $id    = 'resetDefaultRecord';
    protected $name  = 'resetDefaultRecord';
    protected $title = 'resetDefaultRecord';
    protected $icon           = '';

    public function initContent()
    {
        $this->initLoadModalAction(new Modals\ResetDefault());
    }
}
