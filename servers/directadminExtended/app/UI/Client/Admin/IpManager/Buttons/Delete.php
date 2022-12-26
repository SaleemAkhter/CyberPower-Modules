<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\IpManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\IpManager\Modals;

class Delete extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'deleteButton';
    protected $name  = 'deleteButton';
    protected $title = 'deleteButtonTitle';
    protected $icon  = '';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => '',
    ];
    public function initContent()
    {
                // $this->switchToRemoveBtn();

        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', [dataRow.name], null, true)';
    }
    
    public function returnAjaxData()
    {
        $this->setModal(new Modals\Delete());
        
        return parent::returnAjaxData();
    }
}
