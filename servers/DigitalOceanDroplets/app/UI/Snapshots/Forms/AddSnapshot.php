<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Forms;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Providers\Snapshot;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of Create
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class AddSnapshot extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'addSnapshotForm';
    protected $name  = 'addSnapshotForm';
    protected $title = 'addSnapshotForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new Snapshot());
        $this->addField(new Text('snapshotName'));
        $this->loadDataToForm();
    }

}
