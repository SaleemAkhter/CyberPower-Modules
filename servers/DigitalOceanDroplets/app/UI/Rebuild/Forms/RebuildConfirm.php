<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Rebuild\Forms;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class RebuildConfirm extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'rebuildConfirmForm';
    protected $name  = 'rebuildConfirmForm';
    protected $title = 'rebuildConfirmForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new \ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Rebuild\Providers\Rebuild());
        $this->setConfirmMessage('rebuildConfirm');
        $this->addField(new \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Hidden('id'));
        $this->loadDataToForm();
    }

}
