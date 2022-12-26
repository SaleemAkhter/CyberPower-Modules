<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Home\Modals;

use ModulesGarden\Servers\HetznerVps\App\UI\Home\Forms\PasswordResetAction;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Modals\BaseModal;

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
        $this->setModalTitleTypeDanger();
        $this->setSubmitButtonClassesDanger();
        $this->addForm(new PasswordResetAction());
    }

}
