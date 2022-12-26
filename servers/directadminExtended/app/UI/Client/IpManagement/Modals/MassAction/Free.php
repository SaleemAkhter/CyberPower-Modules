<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\IpManagement\Modals\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\IpManagement\Forms;

class Free extends BaseModal implements ClientArea
{
    protected $id    = 'massFreeModal';
    protected $name  = 'massFreeModal';
    protected $title = 'massFreeModal';

    public function initContent()
    {
        $this->setSubmitButtonClassesDanger();
        $this->setModalTitleTypeDanger();
        $this->addForm(new Forms\MassAction\Free());
    }
}
