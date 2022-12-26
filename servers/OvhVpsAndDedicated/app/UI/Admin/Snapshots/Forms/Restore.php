<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Providers\Snapshot;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\Alerts;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;


/**
 * Description of Create
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Restore extends BaseForm implements ClientArea, AdminArea
{
    use Alerts;
    use Lang;

    const RESTORE = 'restore';

    protected $id    = 'restoreSnapshotForm';
    protected $name  = 'restoreSnapshotForm';
    protected $title = 'restoreSnapshotForm';
    protected  $allowedActions = [
        SELF::RESTORE
    ];

    public function initContent()
    {
        $this->loadLang();
        $this->setInternalAlertMessage($this->lang->absoluteTranslate('confirmModal', 'addSnapshotForm', 'snapshotRestore'));
        $this->setInternalAlertMessageRaw(true);
        $this->setInternalAlertMessageType(AlertTypesConstants::DANGER);
        $this->setFormType(SELF::RESTORE);
        $this->setProvider(new Snapshot());
        $this->loadDataToForm();
    }
}
