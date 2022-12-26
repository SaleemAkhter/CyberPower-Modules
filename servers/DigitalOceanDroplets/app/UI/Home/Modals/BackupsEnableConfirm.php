<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Modals;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Forms\BackupsEnableAction;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class BackupsEnableConfirm extends BaseModal  implements  AdminArea
{

    protected $id    = 'backupsEnableConfirmModal';
    protected $name  = 'backupsEnableConfirmModal';
    protected $title = 'backupsEnableConfirmModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->addForm(new BackupsEnableAction());
    }

}
