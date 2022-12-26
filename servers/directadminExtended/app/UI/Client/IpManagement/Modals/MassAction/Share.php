<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\IpManagement\Modals\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\IpManagement\Forms;

class Share extends BaseModal implements ClientArea
{
    protected $id    = 'massShareModal';
    protected $name  = 'massShareModal';
    protected $title = 'massShareModal';

    public function initContent()
    {
        $this->replaceSubmitButtonClasses(['lu-btn lu-btn--success submitForm']);
        $this->setModalTitleTypeSuccess();
        $this->addForm(new Forms\MassAction\Share());
    }
}
