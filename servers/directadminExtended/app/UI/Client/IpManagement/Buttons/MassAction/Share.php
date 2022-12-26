<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\IpManagement\Buttons\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\IpManagement\Modals;

class Share extends ButtonMassAction implements ClientArea
{
    protected $id    = 'shareManyButton';
    protected $name  = 'shareManyButton';
    protected $title = 'shareManyButton';
    protected $icon  = '';
    protected $class=['lu-btn', 'lu-btn--link',  'lu-btn--primary'];

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new Modals\MassAction\Share());

        return parent::returnAjaxData();
    }
}
