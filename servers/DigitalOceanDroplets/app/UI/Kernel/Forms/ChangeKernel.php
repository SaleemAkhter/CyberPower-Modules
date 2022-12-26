<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Kernel\Forms;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Kernel\Providers\Kernel;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of Create
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ChangeKernel extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'changeKerneltForm';
    protected $name  = 'changeKerneltForm';
    protected $title = 'changeKerneltForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Kernel());
        $this->setConfirmMessage('confirmChangeKernel');
        $this->addField(new Hidden('id'));
        $this->loadDataToForm();
    }

}
