<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Modals;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Forms;

class ViewCertificate extends BaseEditModal implements ClientArea
{
    protected $id    = 'viewCertModal';
    protected $name  = 'viewCertModal';
    protected $title = 'viewCertModal';

    public function initContent()
    {
        $this->addForm(new Forms\ViewCertificate());
    }

    public function getActionButtons()
    {
        $buttons = parent::getActionButtons();
        unset($buttons['baseAcceptButton']);

        return $buttons;
    }
}
