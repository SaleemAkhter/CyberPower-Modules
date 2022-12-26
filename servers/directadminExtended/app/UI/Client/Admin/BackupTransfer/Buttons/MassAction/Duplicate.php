<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Buttons\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Providers;

class Duplicate extends ButtonMassAction implements ClientArea
{
    protected $id    = 'duplicateButton';
    protected $name  = 'duplicateButton';
    protected $title = 'duplicateButton';
    protected $icon  = '';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'ajaxAction($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $provider=new Providers\Schedule();
        return $provider->duplicate();
    }
}
