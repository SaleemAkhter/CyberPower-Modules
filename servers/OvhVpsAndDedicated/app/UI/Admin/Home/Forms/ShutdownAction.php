<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Providers\Shutdown;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of RebuildVM
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 * @deprecated
 */
class ShutdownAction extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'shutdownActionForm';
    protected $name  = 'shutdownActionForm';
    protected $title = 'shutdownActionForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Shutdown());
        $this->setConfirmMessage('confirmShutodown');
        $this->loadDataToForm();
    }

}
