<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Forms;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Providers\Snapshot;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of Create
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class DeleteSnapshot extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'deleteSnapshotForm';
    protected $name  = 'deleteSnapshotForm';
    protected $title = 'deleteSnapshotForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE);
        $this->setProvider(new Snapshot());
        $this->setConfirmMessage('confirmDelete');
        $this->addField(new Hidden('id'));
        $this->loadDataToForm();
    }

}
