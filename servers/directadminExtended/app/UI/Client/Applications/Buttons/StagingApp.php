<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Modals;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdownItem;

class StagingApp extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'stagingAppButton';
    protected $name  = 'stagingAppButton';
    protected $title = 'stagingAppButton';
    protected $icon  = 'lu-zmdi lu-zmdi-forward';

    public function __construct($baseId = null)
    {
        parent::__construct($baseId);

        $this->setHideByColumnValue('canStaging', '0');
    }

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\StagingApp());
        return parent::returnAjaxData();
    }
}
