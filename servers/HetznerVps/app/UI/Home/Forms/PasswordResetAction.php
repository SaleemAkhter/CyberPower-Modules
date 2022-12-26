<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Home\Forms;

use ModulesGarden\Servers\HetznerVps\App\UI\Home\Providers\PasswordReset;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class PasswordResetAction extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'passwrodResetActionForm';
    protected $name  = 'passwrodResetActionForm';
    protected $title = 'passwrodResetActionForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new PasswordReset());
        $this->setConfirmMessage('confirmResetPassword');
        $this->loadDataToForm();
    }

}
