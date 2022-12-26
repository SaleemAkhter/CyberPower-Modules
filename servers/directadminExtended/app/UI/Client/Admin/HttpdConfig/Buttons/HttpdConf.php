<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\HttpdConfig\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\HttpdConfig\Modals;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

class HttpdConf extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'infoButton';
    protected $name  = 'infoButton';
    protected $title = 'infoButton';
    protected $icon  = 'lu-btn__icon lu-zmdi lu-zmdi-info-outline';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\HttpdConf());

        return parent::returnAjaxData();
    }
}
