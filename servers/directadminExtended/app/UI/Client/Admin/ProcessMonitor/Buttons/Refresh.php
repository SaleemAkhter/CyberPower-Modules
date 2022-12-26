<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ProcessMonitor\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ProcessMonitor\Modals;

class Refresh extends ButtonDataTableModalAction implements ClientArea
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\DisableButtonByColumnValue;


    protected $id    = 'refreshButton';
    protected $name  = 'refreshButton';
    protected $title = 'refreshButton';
    protected $icon = '';
    protected $class=['lu-btn', 'lu-btn--primary'];

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'updateProjects()';
    }

    public function returnAjaxData()
    {

    }
}
