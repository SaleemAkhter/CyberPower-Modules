<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Forms;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Providers\Snapshot;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of Create
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class RestoreSnapshot extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'restoreSnapshotForm';
    protected $name  = 'restoreSnapshotForm';
    protected $title = 'restoreSnapshotForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Snapshot());
        $this->setConfirmMessage('confirmRestore');
        $this->addField(new Hidden('id'));
        $this->loadDataToForm();
    }

}
