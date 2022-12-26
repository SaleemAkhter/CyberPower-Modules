<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MessageSystem\Modals\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MessageSystem\Forms;

class MarkRead extends BaseModal implements ClientArea
{
    protected $id    = 'massMarkReadModal';
    protected $name  = 'massMarkReadModal';
    protected $title = 'massMarkReadModal';

    public function initContent()
    {
        $this->replaceSubmitButtonClasses(['lu-btn lu-btn--success submitForm']);
        $this->setModalTitleTypeSuccess();
        $this->addForm(new Forms\MassAction\MarkRead());
    }
}
