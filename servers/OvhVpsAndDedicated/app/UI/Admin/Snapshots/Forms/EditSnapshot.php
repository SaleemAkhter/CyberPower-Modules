<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Providers\Snapshot;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
/**
 * Class EditSnapshot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class EditSnapshot extends BaseForm implements ClientArea, AdminArea
{
    protected $id    = 'editSnapshotForm';
    protected $name  = 'editSnapshotForm';
    protected $title = 'editSnapshotForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Snapshot());
        $this->addField(new Text('description'));
        $this->loadDataToForm();
    }

}