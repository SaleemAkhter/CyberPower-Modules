<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Buttons\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Modals\MassAction\AuthorizeSshKeysModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction;

class AuthorizeSshKeysButton extends ButtonMassAction implements ClientArea
{
    protected $id    = 'authorizeManyButton';
    protected $name  = 'authorizeManyButton';
    protected $title = 'authorizeManyButton';
    protected $icon  = 'icon-in-button lu-zmdi lu-zmdi-check';

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
    }

    public function returnAjaxData()
    {
        $this->setModal(new AuthorizeSshKeysModal());

        return parent::returnAjaxData();
    }
}