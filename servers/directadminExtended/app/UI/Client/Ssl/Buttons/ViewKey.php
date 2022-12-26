<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Modals;

class ViewKey extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'viewButton';
    protected $name  = 'viewButton';
    protected $title = 'viewButton';
    protected $class = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-btn--default lu-tooltip'];
    protected $icon  = 'lu-btn__icon  lu-zmdi lu-zmdi-eye';

    public function initContent()
    {


        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', [dataRow.allData], null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\ViewKey());

        return parent::returnAjaxData();
    }
}
