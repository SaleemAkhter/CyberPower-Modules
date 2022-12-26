<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Home\Forms;

use ModulesGarden\Servers\HetznerVps\App\UI\Home\Providers\PowerOn;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class PowerOnAction extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'powerOnActionForm';
    protected $name  = 'powerOnActionForm';
    protected $title = 'powerOnActionForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new PowerOn());
        $this->setConfirmMessage('confirmPowerOn');
        $this->loadDataToForm();
    }

}
