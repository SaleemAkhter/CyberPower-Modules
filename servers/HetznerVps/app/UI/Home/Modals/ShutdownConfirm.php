<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Home\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Home\Forms\ShutdownAction;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class ShutdownConfirm extends BaseModal implements ClientArea, AdminArea
{

    protected $id    = 'shutdownConfirmModal';
    protected $name  = 'shutdownConfirmModal';
    protected $title = 'shutdownConfirmModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->setModalTitleTypeDanger();
        $this->setSubmitButtonClassesDanger();
        $this->addForm(new ShutdownAction());
    }

}
