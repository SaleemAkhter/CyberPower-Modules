<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Providers\Snapshot;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\Alerts;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of Create
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class AddSnapshot extends BaseForm implements ClientArea, AdminArea
{
    use Alerts;
    use Lang;

    protected $id    = 'addSnapshotForm';
    protected $name  = 'addSnapshotForm';
    protected $title = 'addSnapshotForm';

    public function initContent()
    {
        $this->loadLang();
        $this->setInternalAlertMessage($this->lang->absoluteTranslate('confirmModal', 'addSnapshotForm', 'snapshotMessage'));
        $this->setInternalAlertMessageRaw(true);

        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new Snapshot());
        $this->addField(new Text('description'));
        $this->loadDataToForm();
    }

}
