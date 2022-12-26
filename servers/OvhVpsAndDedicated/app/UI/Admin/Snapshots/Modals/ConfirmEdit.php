<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Modals;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseEditModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Modals\BaseModal;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Forms\EditSnapshot as EditSnapshotForm;

/**
 * Class ConfirmEdit
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ConfirmEdit extends BaseEditModal implements ClientArea, AdminArea
{

    protected $id    = 'confirmEditModal';
    protected $name  = 'confirmEditModal';
    protected $title = 'confirmEditModal';

    public function initContent()
    {
        $this->setModalSizeMedium();
        $this->addForm(new EditSnapshotForm());
    }

}