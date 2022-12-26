<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Modals;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Forms\PasswordResetAction;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\BaseModal;

/**
 * Description of Restore
 *
 * @author Mateusz Pawlowski <mateusz.pawlowski94@onet.pl>
 */
class PasswordResetConfirm extends BaseModal implements ClientArea, AdminArea
{

    protected $id    = 'passwordResetModal';
    protected $name  = 'passwordResetModal';
    protected $title = 'passwordResetModal';

    public function initContent()
    {
        $this->setModalSizeLarge();
        $this->setConfirmButtonDanger();
        $this->addForm(new PasswordResetAction());
    }

}
