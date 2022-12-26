<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Modals;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ModalActionButtons\BaseAcceptButton;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Forms;

class PushToLive extends BaseModal implements ClientArea
{
    protected $id    = 'pushToLiveModal';
    protected $name  = 'pushToLiveModal';
    protected $title = 'pushToLiveModal';

    public function initContent()
    {
        $this->addForm(new Forms\PushToLive());
    }
}