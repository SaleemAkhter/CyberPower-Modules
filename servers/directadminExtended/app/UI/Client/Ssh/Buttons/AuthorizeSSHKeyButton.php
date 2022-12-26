<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Modals\AuthorizeSSHKeyModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction;

class AuthorizeSSHKeyButton extends ButtonDataTableModalAction implements ClientArea
{
    protected $id    = 'authorizeSSHKeyButton';
    protected $name  = 'authorizeSSHKeyButton';
    protected $title = 'authorizeSSHKeyButton';
    protected $icon  = 'icon-in-button lu-zmdi lu-zmdi-check';

    public function initContent()
    {
        $this->setDisableByColumnValue('authorized', true);
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new AuthorizeSSHKeyModal());

        return parent::returnAjaxData();
    }
}
