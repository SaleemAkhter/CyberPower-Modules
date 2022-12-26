<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Forms;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Providers\PowerOff;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class PowerOffAction extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'powerOffActionForm';
    protected $name  = 'powerOffActionForm';
    protected $title = 'powerOffActionForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new PowerOff());
        $this->setConfirmMessage('confirmPowerOff');
        $this->loadDataToForm();
    }

}
