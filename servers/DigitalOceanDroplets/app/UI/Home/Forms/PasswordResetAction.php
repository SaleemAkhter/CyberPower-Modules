<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Forms;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Providers\PasswordReset;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\FormConstants;

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
