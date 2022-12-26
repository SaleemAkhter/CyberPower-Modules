<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ServiceMonitor\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ServiceMonitor\Modals;

class Refresh extends ButtonDataTableModalAction implements ClientArea
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\DisableButtonByColumnValue;


    protected $id    = 'backupSettingButton';
    protected $name  = 'backupSettingButton';
    protected $title = 'backupSettingButton';
    protected $icon = '';
    protected $class=['lu-btn', 'lu-btn--primary'];

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        // $this->setModal(new Modals\ServiceMonitor());
        // return parent::returnAjaxData();
    }
}

