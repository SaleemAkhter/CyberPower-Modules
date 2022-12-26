<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SshKey\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SshKey\Modals;

class Paste extends ButtonCreate implements ClientArea
{
    protected $id    = 'pasteButton';
    protected $name  = 'pasteButton';
    protected $title = 'pasteButton';
    protected $class          = ['lu-btn lu-btn--primary'];
    protected $icon           = '';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\Paste());
        return parent::returnAjaxData();
    }
}
