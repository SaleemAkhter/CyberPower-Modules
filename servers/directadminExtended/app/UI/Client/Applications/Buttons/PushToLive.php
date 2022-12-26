<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Modals;

class PushToLive extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'pushToLiveButton';
    protected $name  = 'pushToLiveButton';
    protected $title = 'pushToLiveButton';
    protected $icon  = 'lu-zmdi lu-zmdi-repeat';

    public function __construct($baseId = null)
    {
        parent::__construct($baseId);

        $this->setHideByColumnValue('staging', '0');
    }

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\PushToLive());
        return parent::returnAjaxData();
    }
}