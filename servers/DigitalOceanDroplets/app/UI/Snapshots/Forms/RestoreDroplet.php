<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Forms;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Providers\Snapshot;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of Create
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class RestoreDroplet extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'restoreDropletForm';
    protected $name  = 'restoreDropletForm';
    protected $title = 'restoreDropletForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Snapshot());
        $this->setConfirmMessage('confirmRestore');
        $this->addField(new Hidden('id'));
        $this->loadDataToForm();
    }

}
