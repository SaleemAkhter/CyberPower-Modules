<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Providers\Snapshot;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of Create
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
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
        $this->loadDataToForm();
    }

}
